<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
      ->select('persona.nombre', 'persona.carnet_identidad', 'chofer.licencia_numero', 'persona.id')
      ->join('persona', 'chofer.persona_id', '=', 'persona.id')
      ->get();
  }

  public static function renderChoferes(): Response
  {
    return Inertia::render('Choferes', ['choferes' => self::getChoferes()]);
  }
}
