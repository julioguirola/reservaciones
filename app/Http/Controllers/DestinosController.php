<?php

namespace App\Http\Controllers;

use App\Models\Destino;
use App\Models\Profesor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DestinosController extends Controller
{
  public function editTarifa(Request $req)
  {
    $destino = Destino::find($req->id);
    $destino->precio = $req->precio;
    $destino->save();
    return $destino;
  }

  public static function getDestinosViaje($viaje_id)
  {
    return Profesor::select('destino.nombre as destino')
      ->distinct('destino')
      ->join('destino', 'profesor.destino_id', '=', 'destino.id')
      ->join('profesor_viaje', 'profesor_viaje.profesor_id', '=', 'profesor.id')
      ->where('profesor_viaje.viaje_id', $viaje_id)
      ->limit(4)
      ->get();
  }

  public static function renderDestinos()
  {
    return Inertia::render('Destinos', ['destinos' => Destino::all()]);
  }
}
