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
  });
