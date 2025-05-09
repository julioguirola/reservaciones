<?php

namespace App\Http\Controllers;

use App\Models\Profesor;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProfesoresController extends Controller
{
  public static function countProfesoresViajes(string $viaje_id): int
  {
    return DB::table('viaje_profesor')->where('viaje_id', $viaje_id)->count();
  }

  public static function getProfesores()
  {
    return Profesor::select(
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

  public static function deleteProfesor(string $profesor_id)
  {
    $profesor = Profesor::where('persona_id', $profesor_id);
    $profesor->delete();
    return $profesor;
  }
  public static function renderProfesores()
  {
    return Inertia::render('Profesores', ['profesores' => ProfesoresController::getProfesores()]);
  }
}
