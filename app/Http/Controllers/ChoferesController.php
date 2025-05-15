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
use stdClass;

class ChoferesController extends Controller
{
  /**
   * @return Collection<int,stdClass>
   */
  public static function getChoferes(): Collection
  {
    return DB::table('chofer')
      ->select('chofer.id', 'persona.id as persona_id', 'persona.nombre', 'persona.carnet_identidad', 'chofer.licencia_numero')
      ->join('persona', 'chofer.persona_id', '=', 'persona.id')
      ->where('chofer.deleted_at', null)
      ->get();
  }

  public static function getChofer(string $chofer_id)
  {
    return DB::table('chofer')
      ->select('chofer.id', 'persona.id as persona_id', 'persona.nombre', 'persona.carnet_identidad', 'chofer.licencia_numero')
      ->join('persona', 'chofer.persona_id', '=', 'persona.id')
      ->where('chofer.deleted_at', null)
      ->where('chofer.id', $chofer_id)
      ->get();
  }

  public static function renderChoferes(): Response
  {
    return Inertia::render('Choferes', ['choferes' => self::getChoferes()]);
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
  public static function countViajesChofer(string $chofer_id): int
  {
    return DB::table('viaje')->where('chofer_id', $chofer_id)->count();
  }
  public static function crearProfesor(Request $request) {
      $nuevo_profesor = new ;
  }

}
