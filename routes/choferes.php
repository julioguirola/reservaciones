<?php

use App\Http\Controllers\ChoferesController;

Route::prefix('choferes')
  ->name('choferes')
  ->group(function () {
    Route::get('/', [ChoferesController::class, 'renderChoferes'])->middleware(['auth', 'verified']);
    Route::delete('/{chofer_id}', [ChoferesController::class, 'deleteChofer'])
      ->middleware(['auth', 'verified'])
      ->name('.eliminar');
    Route::put('/{chofer_id}', [ChoferesController::class, 'editChofer'])
      ->middleware(['auth', 'verified'])
      ->name('.editar');
    Route::get('/{chofer_id}', [ChoferesController::class, 'infoChofer'])
      ->middleware(['auth', 'verified'])
      ->name('.info');
    Route::get('/{chofer_id}/viajes', [ChoferesController::class, 'getViajesChofer'])
      ->middleware(['auth', 'verified'])
      ->name('.viajes');
    Route::get('/viajes/count/{chofer_id}', [ChoferesController::class, 'countViajesChofer'])
      ->middleware(['auth', 'verified'])
      ->name('.viajes.count');
  });
