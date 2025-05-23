<?php

use App\Http\Controllers\ViajesController;

Route::prefix('viajes')
  ->name('viajes')
  ->group(function () {
    Route::get('/', [ViajesController::class, 'renderViajes'])->middleware(['auth', 'verified']);
    Route::get('/{viaje_id}', [ViajesController::class, 'infoViaje'])
      ->middleware(['auth', 'verified'])
      ->name('.info');
    Route::get('/{viaje_id}/profesores', [ViajesController::class, 'getProfesoresViaje'])
      ->middleware(['auth', 'verified'])
      ->name('.profesores');
  });
