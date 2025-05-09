<?php

namespace App\Http\Controllers;

use App\Models\Destino;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Inertia;
use Inertia\Response;

class DestinosController extends Controller
{
  /**
   * @return Collection<int,Destino>
   */
  public static function getDestinos(): Collection
  {
    return Destino::all();
  }
  /**
   * @return Response
   */
  public static function renderDestinos(): Response
  {
    return Inertia::render('Destinos', ['destinos' => self::getDestinos()]);
  }
}
