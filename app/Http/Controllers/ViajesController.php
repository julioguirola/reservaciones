<?php

namespace App\Http\Controllers;

use App\Models\Viaje;
use Illuminate\Http\Request;
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
    $viajes = DB::table('viaje')
      ->select('viaje.id', 'viaje.fecha', 'persona.nombre as chofer_nombre')
      ->join('chofer', 'chofer.id', '=', 'viaje.chofer_id')
      ->join('persona', 'persona.id', '=', 'chofer.persona_id')
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
      $viaje->recaudado = $recaudado;

      return $viaje;
    }, $viajes);

    return ['viajes' => $viajes_destinos, 'viajes_cant' => Viaje::count()];
  }

  public function renderViajes(Request $request): Response
  {
    return Inertia::render('Viajes', ['viajes' => self::getViajes($request)['viajes'], 'viajes_cant' => Viaje::count()]);
  }

  public static function getProfesoresViaje(int $viaje_id)
  {
    $profesores_viaje = DB::table('profesor_viaje')
      ->select('profesor_viaje.profesor_id', 'persona.id as persona_id', 'persona.nombre', 'destino.nombre as destino', 'destino.precio as tarifa')
      ->where('profesor_viaje.viaje_id', $viaje_id)
      ->join('persona', 'profesor.persona_id', '=', 'persona.id')
      ->join('profesor', 'profesor.id', '=', 'profesor_viaje.profesor_id')
      ->join('destino', 'profesor.destino_id', '=', 'destino.id')
      ->get()
      ->all();

    $destinos_cant_profesores = [];

    foreach ($profesores_viaje as $profesor) {
      $destinos_cant_profesores[$profesor->destino] = ($destinos_cant_profesores[$profesor->destino] ?? 0) + 1;
    }

    return Inertia::render('ProfesoresViaje', [
      'profesores' => $profesores_viaje,
      'viaje_id' => $viaje_id,
      'destinos_cant_profesores' => $destinos_cant_profesores,
    ]);
  }

  public static function getRecaudadoViaje(int $viaje_id)
  {
    return $tarifas_viaje = DB::table('profesor_viaje')
      ->select('destino.precio')
      ->join('profesor', 'profesor.id', '=', 'profesor_viaje.profesor_id')
      ->join('destino', 'profesor.destino_id', '=', 'destino.id')
      ->where('profesor_viaje.viaje_id', $viaje_id)
      ->where('profesor.tarifa', true)
      ->sum('destino.precio');
  }
  public static function crearViaje(Request $request) {}
  public static function addProfesorViaje(Request $request)
  {
    $data = $request->all();
    DB::table('profesor_viaje')->insert([
      'profesor_id' => $data['profesor_id'],
      'viaje_id' => $data['viaje_id'],
    ]);
  }
  public static function changeChoferViaje(Request $request, string $viaje_id) {}
}
