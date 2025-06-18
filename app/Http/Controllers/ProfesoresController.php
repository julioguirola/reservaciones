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
  public static function getProfesores(Request $request)
  {
    $query = DB::table('profesor')
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
      ->selectRaw(
        "(select count(*) from profesor_viaje pv
        join viaje v on pv.viaje_id = v.id
        where pv.profesor_id = profesor.id
        and (cast(strftime('%m', v.fecha) as integer) between 4 and 7)
        ) as cant_viajes_abril_julio",
      )
      ->selectRaw(
        "(select count(*) from profesor_viaje pv
        join viaje v on pv.viaje_id = v.id
        where pv.profesor_id = profesor.id
        and (cast(strftime('%m', v.fecha) as integer) between 8 and 9)
        ) as cant_viajes_agosto_septiembre",
      )
      ->selectRaw(
        "(select count(*) from profesor_viaje pv
        join viaje v on pv.viaje_id = v.id
        where pv.profesor_id = profesor.id
        and (cast(strftime('%m', v.fecha) as integer) between 10 and 12
        or cast(strftime('%m', v.fecha) as integer) between 1 and 3)
        ) as cant_viajes_octubre_marzo",
      )
      ->selectRaw(
        '(select count(*) from profesor_viaje pv
        where pv.profesor_id = profesor.id
        ) as cant_viajes',
      )
      ->join('persona', 'profesor.persona_id', '=', 'persona.id')
      ->join('destino', 'profesor.destino_id', '=', 'destino.id')
      ->join('facultad', 'profesor.facultad_id', '=', 'facultad.id')
      ->join('asignatura', 'profesor.asignatura_id', '=', 'asignatura.id')
      ->where('profesor.deleted_at', null);

    $query_count = DB::table('profesor')
      ->select('persona.carnet_identidad')
      ->selectRaw(
        '(select count(*) from profesor_viaje pv
        where pv.profesor_id = profesor.id
        ) as cant_viajes',
      )
      ->join('persona', 'profesor.persona_id', '=', 'persona.id')
      ->where('profesor.deleted_at', null);

    $parameters = $request->all();

    if (array_key_exists('cant_viajes', $parameters)) {
      $cant_viajes_filter = $request->query('cant_viajes');
      $cv_filter = $cant_viajes_filter + 0;
      $query = $query->where('cant_viajes', $cv_filter);
      $query_count = $query_count->where('cant_viajes', $cv_filter);
    }

    if (array_key_exists('carnet', $parameters)) {
      $carnet_filter = $request->query('carnet');
      $query = $query->where('carnet_identidad', 'LIKE', '%' . $carnet_filter . '%');
      $query_count = $query_count->where('carnet_identidad', 'LIKE', '%' . $carnet_filter . '%');
    }

    if (array_key_exists('cant_viajes', $parameters) || array_key_exists('carnet', $parameters)) {
      $count = count($query_count->get()->all());
      $profesores = $query
        ->offset($request->query('page', 0) * 5)
        ->limit(5)
        ->get();
      return [
        'profesores' => $profesores,
        'profesores_cant' => $count,
      ];
    }

    return [
      'profesores' => $query
        ->offset($request->query('page', 0) * 5)
        ->limit(5)
        ->get(),
      'profesores_cant' => Profesor::count(),
    ];
  }
  public static function renderProfesores(Request $request): Response
  {
    return Inertia::render('Profesores', [
      'profesores' => self::getProfesores($request)['profesores'],
      'profesores_cant' => Profesor::count(),
    ]);
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
