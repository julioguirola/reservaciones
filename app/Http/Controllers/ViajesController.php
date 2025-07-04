<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use App\Models\Viaje;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use stdClass;

class ViajesController extends Controller
{
  /**
   * @return array<int,stdClass>
   */
  public static function getViajes(Request $request)
  {
    $count = 0;
    $query = DB::table('viaje')
      ->select('viaje.id', 'viaje.fecha', 'persona.nombre as chofer_nombre', 'viaje.chofer_id')
      ->join('chofer', 'chofer.id', '=', 'viaje.chofer_id')
      ->join('persona', 'persona.id', '=', 'chofer.persona_id');

    $fecha_filter = $request->query('fecha');

    if ($fecha_filter) {
      $query = $query->whereDate('fecha', $fecha_filter);
      $count = Viaje::whereDate('fecha', $fecha_filter)->count();
    } else {
      $count = Viaje::count();
    }

    $viajes = $query
      ->offset($request->query('page', 0) * 8)
      ->limit(8)
      ->get()
      ->all();

    $viajes_destinos = array_map(function ($viaje) {
      $destinos = DB::table('destino')
        ->select('destino.nombre')
        ->distinct('destino')
        ->join('profesor', 'profesor.destino_id', '=', 'destino.id')
        ->join('profesor_viaje', 'profesor_viaje.profesor_id', '=', 'profesor.id')
        ->where('profesor_viaje.viaje_id', $viaje->id)
        ->get()
        ->all();

      $profesores_count = DB::table('profesor_viaje')->where('viaje_id', $viaje->id)->count();

      $recaudado = self::getRecaudadoViaje($viaje->id);

      $viaje->destinos = array_map(function ($destino) {
        return $destino->nombre;
      }, $destinos);
      $viaje->profesores_count = $profesores_count;
      $viaje->recaudado = round($recaudado, 2);
      $realizado = Carbon::now()->greaterThan($viaje->fecha);
      if ($realizado) {
        $viaje->realizado = true;
      }
      return $viaje;
    }, $viajes);

    return ['viajes' => $viajes_destinos, 'viajes_cant' => $count];
  }

  public function renderViajes(Request $request): Response
  {
    return Inertia::render('Viajes', ['viajes' => self::getViajes($request)['viajes'], 'viajes_cant' => Viaje::count()]);
  }

  public static function getProfesoresViaje(int $viaje_id, Request $request)
  {
    if ($request->query('not_in_viaje')) {
      $profesores_not_viaje = DB::table('profesor as p')
        ->join('persona as per', 'p.persona_id', '=', 'per.id')
        ->join('destino as d', 'p.destino_id', '=', 'd.id')
        ->whereNotIn('p.id', function ($query) use ($viaje_id) {
          $query->select('pv.profesor_id')->from('profesor_viaje as pv')->where('pv.viaje_id', $viaje_id);
        })
        ->where('p.deleted_at', null)
        ->orderBy('p.id')
        ->select(['per.id as persona_id', 'per.nombre', 'per.carnet_identidad', 'd.nombre as destino', 'p.id'])
        ->get()
        ->all();

      return ['profesores_not_viaje' => $profesores_not_viaje];
    } else {
      $profesores_viaje = DB::table('profesor_viaje')
        ->select('profesor_viaje.profesor_id as id', 'persona.id as persona_id', 'persona.nombre', 'destino.nombre as destino', 'profesor.destino_id')
        ->where('profesor_viaje.viaje_id', $viaje_id)
        ->join('persona', 'profesor.persona_id', '=', 'persona.id')
        ->join('profesor', 'profesor.id', '=', 'profesor_viaje.profesor_id')
        ->join('destino', 'profesor.destino_id', '=', 'destino.id')
        ->get()
        ->all();

      $viaje = DB::table('viaje')->where('id', $viaje_id)->first();
      $realizado = Carbon::now()->greaterThan($viaje->fecha);
      $count = DB::table('profesor_viaje')->where('viaje_id', $viaje_id)->count();

      return Inertia::render('ProfesoresViaje', [
        'profesores' => $profesores_viaje,
        'viaje_id' => $viaje_id,
        'realizado' => $realizado,
        'lleno' => $count == 48,
        'destinos' => DB::table('destino')->select()->get()->all(),
      ]);
    }
  }

  public static function getRecaudadoViaje(int $viaje_id)
  {
    return $tarifas_viaje = DB::table('profesor_viaje')
      ->selectRaw('SUM(d.precio) as recaudado')
      ->join('profesor as p', 'profesor_viaje.profesor_id', '=', 'p.id')
      ->join('viaje as v', 'profesor_viaje.viaje_id', '=', 'v.id')
      ->join('destino as d', 'd.id', '=', 'p.destino_id')
      ->where('v.id', $viaje_id)
      ->whereExists(function ($query) {
        $query
          ->select(DB::raw(1))
          ->from('profesor_viaje as pv2')
          ->join('viaje as v2', 'v2.id', '=', 'pv2.viaje_id')
          ->whereColumn('pv2.profesor_id', 'p.id')
          ->whereRaw('DATE(v2.fecha) < DATE(v.fecha)')
          ->whereNotBetween(DB::raw("CAST(STRFTIME('%m', v2.fecha) AS INTEGER)"), [8, 9])
          ->limit(1);
      })
      ->first()->recaudado;
  }
  public static function addProfesorViaje(int $viaje_id, Request $request)
  {
    try {
      $count = DB::table('profesor_viaje')->where('viaje_id', $viaje_id)->count();
      if ($count == 48) {
        return response()->json(['error' => 'Viaje lleno'], 400);
      }
      $data = $request->all();
      DB::table('profesor_viaje')->insert([
        'profesor_id' => $data['profesor_id'],
        'viaje_id' => $viaje_id,
      ]);
      return ['succes' => 'Profesor agregado al viaje'];
    } catch (QueryException $e) {
      return response()->json(['error' => 'Error agregando profesor al viaje'], 400);
    }
  }
  public static function changeChoferViaje(Request $request, string $viaje_id) {}

  public static function removeProfesorViaje(Request $request, string $viaje_id)
  {
    $viaje = DB::table('viaje')->where('id', $viaje_id)->first();
    $ocurrido = Carbon::now()->greaterThan($viaje->fecha);
    if ($ocurrido) {
      return response()->json(['error' => 'No se puede eliminar un profesor de un viaje que ya ha ocurrido'], 400);
    }
    $data = $request->all();
    $deleted = DB::table('profesor_viaje')->where('profesor_id', $data['profesor_id'])->where('viaje_id', $viaje_id)->delete();
    if ($deleted) {
      return response()->json(['message' => 'Profesor eliminado del viaje'], 200);
    } else {
      return response()->json(['error' => 'Error al eliminar el profesor del viaje'], 500);
    }
  }

  public static function deleteViaje(string $viaje_id)
  {
    try {
      $data = DB::table('viaje')->where('id', $viaje_id)->delete();
      return ['deleted' => $data];
    } catch (QueryException $e) {
      return ['error' => 'Error eliminando viaje'];
    }
  }

  public static function getViaje(string $viaje_id)
  {
    return DB::table('viaje')->select()->where('id', $viaje_id)->get()[0];
  }

  public static function editViaje(Request $request, string $viaje_id)
  {
    $data = $request->all();
    $validator = Validator::make($request->all(), [
      'fecha' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'errors' => $validator->errors(),
        ],
        422,
      );
    }

    Viaje::where('id', $viaje_id)->update([
      'fecha' => $data['fecha'],
      'chofer_id' => $data['chofer_id'],
    ]);

    return self::getViaje($viaje_id);
  }

  public static function crearViaje(Request $request)
  {
    $data = $request->all();
    $validator = Validator::make($request->all(), [
      'fecha' => 'required',
      'chofer_id' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'errors' => $validator->errors(),
        ],
        422,
      );
    }

    $viaje = Viaje::create([
      'fecha' => $data['fecha'],
      'chofer_id' => $data['chofer_id'],
    ]);

    return ['viaje' => $viaje];
  }
}
