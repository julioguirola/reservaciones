<?php

namespace App\Http\Controllers;

use App\Models\Chofer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChoferesController extends Controller
{
  public static function getChoferes()
  {
    return Chofer::select('persona.nombre', 'persona.carnet_identidad', 'chofer.licencia_numero', 'persona.id')
      ->join('persona', 'chofer.persona_id', '=', 'persona.id')
      ->get();
  }

  public static function renderChoferes()
  {
    return Inertia::render('Choferes', ['choferes' => $this->getChoferes()]);
  }
}
