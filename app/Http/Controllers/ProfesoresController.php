<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use stdClass;

class ProfesoresController extends Controller
{
  /**
   * @return Collection<int,stdClass>
   */
  public static function getProfesores(): Collection
  {
    return DB::table('profesor')
      ->select(
        'persona.id as profesor_id',
        'persona.nombre',
        'persona.carnet_identidad',
        'destino.nombre as destino',
        'facultad.nombre as facultad',
        'asignatura.nombre as asignatura',
      )
      ->join('persona', 'profesor.persona_id', '=', 'persona.id')
      ->join('destino', 'profesor.destino_id', '=', 'destino.id')
      ->join('facultad', 'profesor.facultad_id', '=', 'facultad.id')
      ->join('asignatura', 'profesor.asignatura_id', '=', 'asignatura.id')
      ->get();
  }
  public static function renderProfesores(): Response
  {
    return Inertia::render('Profesores', ['profesores' => self::getProfesores()]);
  }
}
