<?php

namespace App\Http\Controllers;

use App\Models\Destino;
use Illuminate\Http\Request;

class DestinosController extends Controller
{
  public function editTarifa(Request $req)
  {
    $destino = Destino::find($req->id);
    $destino->precio = $req->precio;
    $destino->save();
    return $destino;
  }

  public function getTarifas()
  {
    return Destino::all();
  }
}
