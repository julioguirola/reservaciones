<?php

namespace App\Http\Controllers;

use App\Models\Profesor;
use App\Models\Viaje;
use Illuminate\Http\Request;

class ViajesController extends Controller
{
  public function addProfesorViaje(Request $req)
  {
    $viaje = Viaje::find($req->id);
    $profesor = Profesor::find($req->profesor_id);
    $viaje->profesores()->attach($profesor);

    return ['viaje' => $viaje, 'profesor' => $profesor];
  }

  public function getViajes()
  {
    return Viaje::all();
  }

  public function getProfesoresViaje(string $id)
  {
    $viaje = Viaje::find($id);
    return $viaje->profesores;
  }
}
