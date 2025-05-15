<?php

namespace App\Http\Controllers;

use App\Models\Destino;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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
  public static function editDestino(Request $request, string $destino_id)
  {
    $data = $request->all();
    $validator = Validator::make($data, [
      'precio' => 'numeric|min:1|max:300|required',
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'errors' => $validator->errors(),
        ],
        422,
      );
    }

    $destino = Destino::where('id', $destino_id)->update([
      'precio' => $data['precio'],
    ]);

    return $destino;
  }
}
