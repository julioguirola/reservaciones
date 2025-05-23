<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Facultad;
use App\Models\Persona;
use App\Models\Profesor;
use Illuminate\Support\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Validator;
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
        'profesor.id',
        'persona.id as persona_id',
        'persona.nombre',
        'persona.carnet_identidad',
        'destino.nombre as destino',
        'facultad.nombre as facultad',
        'asignatura.nombre as asignatura',
        'destino.id as destino_id',
        'facultad.id as facultad_id',
        'asignatura.id as asignatura_id',
      )
      ->join('persona', 'profesor.persona_id', '=', 'persona.id')
      ->join('destino', 'profesor.destino_id', '=', 'destino.id')
      ->join('facultad', 'profesor.facultad_id', '=', 'facultad.id')
      ->join('asignatura', 'profesor.asignatura_id', '=', 'asignatura.id')
      ->where('profesor.deleted_at', null)
      ->get();
  }
  public static function renderProfesores(): Response
  {
    return Inertia::render('Profesores', ['profesores' => self::getProfesores()]);
  }
  public static function deleteProfesor(string $profesor_id): Profesor
  {
    $profesor = Profesor::find($profesor_id);
    $profesor->delete();
    return $profesor;
  }

  public static function editProfesor(Request $request, string $profesor_id)
  {
    $data = $request->all();
    $validator = Validator::make($data, [
      'nombre' => 'min:5|max:30|required',
      'carnet_identidad' => 'digits:11|required',
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'errors' => $validator->errors(),
        ],
        422,
      );
    }

    Profesor::where('id', $profesor_id)->update([
      'destino_id' => $data['destino_id'],
      'facultad_id' => $data['facultad_id'],
      'asignatura_id' => $data['asignatura_id'],
    ]);

    Persona::where('id', $data['persona_id'])->update([
      'nombre' => $data['nombre'],
      'carnet_identidad' => $data['carnet_identidad'],
    ]);
    return self::getProfesor($profesor_id);
  }
  public static function getAsignaturasYFacultades()
  {
    $asignaturas = Asignatura::all()->all();
    $facultades = Facultad::all()->all();

    return ['asignaturas' => $asignaturas, 'facultades' => $facultades];
  }

  public static function getProfesor(string $profesor_id)
  {
    $profesor = DB::table('profesor')
      ->select(
        'profesor.id',
        'persona.id as persona_id',
        'persona.nombre',
        'persona.carnet_identidad',
        'destino.nombre as destino',
        'facultad.nombre as facultad',
        'asignatura.nombre as asignatura',
        'destino.id as destino_id',
        'facultad.id as facultad_id',
        'asignatura.id as asignatura_id',
      )
      ->join('persona', 'profesor.persona_id', '=', 'persona.id')
      ->join('destino', 'profesor.destino_id', '=', 'destino.id')
      ->join('facultad', 'profesor.facultad_id', '=', 'facultad.id')
      ->join('asignatura', 'profesor.asignatura_id', '=', 'asignatura.id')
      ->where('profesor.deleted_at', null)
      ->where('profesor.id', $profesor_id)
      ->get()[0];

    return $profesor;
  }

  public static function crearProfesor(Request $request)
  {
    $data = $request->all();
    $validator = Validator::make($data, [
      'nombre' => 'min:5|max:30|required',
      'carnet_identidad' => 'digits:11|required',
      'facultad_id' => 'required|numeric',
      'asignatura_id' => 'required|numeric',
      'destino_id' => 'required|numeric',
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
    } catch (QueryException $e) {
      return response()->json(
        [
          'errors' => ['carnet_identidad' => ['unique.constraint']],
        ],
        422,
      );
    }

    $profesor = Profesor::create([
      'persona_id' => $persona->id,
      'destino_id' => $data['destino_id'],
      'asignatura_id' => $data['asignatura_id'],
      'facultad_id' => $data['facultad_id'],
      'tarifa' => false,
    ]);

    return ['profesor' => self::getProfesor($profesor->id)];
  }
  public static function getViajesProfesor(string $profesor_id)
  {
    return DB::table('profesor_viaje')
      ->select('profesor_viaje.profesor_id', 'persona.id as persona_id', 'persona.nombre', 'viaje.id', 'viaje.fecha')
      ->where('profesor_viaje.profesor_id', $profesor_id)
      ->join('persona', 'profesor.persona_id', '=', 'persona.id')
      ->join('profesor', 'profesor.id', '=', 'profesor_viaje.profesor_id')
      ->join('viaje', 'viaje.id', '=', 'profesor_viaje.viaje_id')
      ->get();
  }
}
