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
  public static function getViajes(): Collection
  {
    return DB::table('viaje')
      ->select('viaje.id', 'viaje.fecha', 'persona.nombre as chofer_nombre')
      ->join('chofer', 'chofer.id', '=', 'viaje.chofer_id')
      ->join('persona', 'persona.id', '=', 'chofer.persona_id')
      ->get();
  }

  public function renderViajes(): Response
  {
    return Inertia::render('Viajes', ['viajes' => self::getViajes()]);
  }
  public static function countProfesoresViajes(string $viaje_id): int
  {
    return DB::table('profesor_viaje')->where('viaje_id', $viaje_id)->count();
  }
  /**
   * @param mixed $viaje_id
   * @return Collection<int,stdClass>
   */
  public static function getDestinosViaje($viaje_id): Collection
  {
    return DB::table('destino')
      ->select('destino.nombre as destino')
      ->distinct('destino')
      ->join('profesor', 'profesor.destino_id', '=', 'destino.id')
      ->join('profesor_viaje', 'profesor_viaje.profesor_id', '=', 'profesor.id')
      ->where('profesor_viaje.viaje_id', $viaje_id)
      ->limit(4)
      ->get();
  }
}
