<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use stdClass;

class ViajesController extends Controller
{
  /**
   * @return array<int,stdClass>
   */
  public static function getViajes()
  {
    $viajes = DB::table('viaje')
      ->select('viaje.id', 'viaje.fecha', 'persona.nombre as chofer_nombre')
      ->join('chofer', 'chofer.id', '=', 'viaje.chofer_id')
      ->join('persona', 'persona.id', '=', 'chofer.persona_id')
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

    return $viajes_destinos;
  }

  public function renderViajes(): Response
  {
    return Inertia::render('Viajes', ['viajes' => self::getViajes()]);
  }

  public static function getProfesoresViaje(int $viaje_id)
  {
    return DB::table('profesor_viaje')
      ->select('profesor_viaje.profesor_id', 'persona.id as persona_id', 'persona.nombre', 'destino.nombre as destino', 'destino.precio as tarifa')
      ->where('profesor_viaje.viaje_id', $viaje_id)
      ->join('persona', 'profesor.persona_id', '=', 'persona.id')
      ->join('profesor', 'profesor.id', '=', 'profesor_viaje.profesor_id')
      ->join('destino', 'profesor.destino_id', '=', 'destino.id')
      ->get();
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
