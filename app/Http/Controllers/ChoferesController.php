<?php

namespace App\Http\Controllers;

use App\Models\Chofer;
use App\Models\Persona;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Database\UniqueConstraintViolationException;
use stdClass;

class ChoferesController extends Controller
{
  /**
   * @return Collection<int,stdClass>
   */
  public static function getChoferes(Request $request)
  {
    $choferes = DB::table('chofer')
      ->select('chofer.id', 'persona.id as persona_id', 'persona.nombre', 'persona.carnet_identidad', 'chofer.licencia_numero')
      ->join('persona', 'chofer.persona_id', '=', 'persona.id')
      ->where('chofer.deleted_at', null)
      ->offset($request->query('page', 0) * 5);

    $licencia_numero_filter = $request->query('licencia_numero');

    if ($licencia_numero_filter) {
      $choferes = $choferes->where('chofer.licencia_numero', 'LIKE', '%' . $licencia_numero_filter . '%');
    }

    $choferes = $choferes->limit(5)->get()->all();

    $choferes_viajes = array_map(function ($chofer) {
      $cant_viajes = DB::table('viaje')->where('chofer_id', $chofer->id)->count();
      $chofer->cant_viajes = $cant_viajes;
      return $chofer;
    }, $choferes);

    $count = 0;

    if ($licencia_numero_filter) {
      $count = Chofer::where('chofer.licencia_numero', 'LIKE', '%' . $licencia_numero_filter . '%')->count();
    } else {
      $count = Chofer::count();
    }

    return [
      'choferes' => $choferes_viajes,
      'choferes_cant' => $count,
    ];
  }

  public static function getChofer(string $chofer_id)
  {
    return DB::table('chofer')
      ->select('chofer.id', 'persona.id as persona_id', 'persona.nombre', 'persona.carnet_identidad', 'chofer.licencia_numero')
      ->join('persona', 'chofer.persona_id', '=', 'persona.id')
      ->where('chofer.deleted_at', null)
      ->where('chofer.id', $chofer_id)
      ->get()[0];
  }

  public static function renderChoferes(Request $request): Response
  {
    return Inertia::render('Choferes', ['choferes' => self::getChoferes($request)['choferes'], 'choferes_cant' => Chofer::count()]);
  }
  public static function deleteChofer(string $chofer_id): Chofer
  {
    $chofer = Chofer::find($chofer_id);
    $chofer->delete();
    return $chofer;
  }
  public static function editChofer(Request $request, string $chofer_id)
  {
    $data = $request->all();
    $validator = Validator::make($request->all(), [
      'nombre' => 'min:5|max:30|required',
      'carnet_identidad' => 'digits:11|required',
      'licencia_numero' => 'digits:10|required',
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'errors' => $validator->errors(),
        ],
        422,
      );
    }

    Chofer::where('id', $chofer_id)->update([
      'licencia_numero' => $data['licencia_numero'],
    ]);

    Persona::where('id', $data['persona_id'])->update([
      'nombre' => $data['nombre'],
      'carnet_identidad' => $data['carnet_identidad'],
    ]);
    return self::getChofer($chofer_id);
  }

  public static function getViajesChofer(string $chofer_id)
  {
    return DB::table('viaje')->select('viaje.id', 'viaje.fecha')->where('chofer_id', $chofer_id)->get();
  }
  public static function crearChofer(Request $request)
  {
    $data = $request->all();
    $validator = Validator::make($data, [
      'nombre' => 'min:5|max:30|required',
      'carnet_identidad' => 'digits:11|required',
      'licencia_numero' => 'digits:10|numeric',
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'errors' => $validator->errors(),
        ],
        422,
      );
    }

    try {
      $persona = Persona::create([
        'nombre' => $data['nombre'],
        'carnet_identidad' => $data['carnet_identidad'],
      ]);
      $chofer = Chofer::create([
        'persona_id' => $persona->id,
        'licencia_numero' => $data['licencia_numero'],
      ]);
    } catch (UniqueConstraintViolationException $e) {
      $errors = [];
      if (str_contains($e->errorInfo[2], 'persona.carnet_identidad')) {
        $errors['carnet_identidad'] = ['unique.constraint'];
      }
      if (str_contains($e->errorInfo[2], 'chofer.licencia_numero')) {
        $errors['licencia_numero'] = ['unique.constraint'];
      }

      return response()->json(
        [
          'errors' => $errors,
        ],
        422,
      );
    }

    return ['chofer' => self::getChofer($chofer->id)];
  }
}
