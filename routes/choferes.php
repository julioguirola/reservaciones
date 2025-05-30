<?php

use App\Http\Controllers\ChoferesController;

Route::prefix('choferes')
  ->name('choferes')
  ->group(function () {
    Route::get('/', [ChoferesController::class, 'renderChoferes'])->middleware(['auth', 'verified']);
    Route::get('/data', [ChoferesController::class, 'getChoferes'])
      ->middleware(['auth', 'verified'])
      ->name('.data');
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
    Route::post('/', [ChoferesController::class, 'crearChofer'])
      ->middleware(['auth', 'verified'])
      ->name('.crear');
  });
