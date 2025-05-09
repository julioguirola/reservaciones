<?php

use App\Http\Controllers\ViajesController;

Route::prefix('viajes')
  ->name('viajes')
  ->group(function () {
    Route::get('/', [ViajesController::class, 'renderViajes'])->middleware(['auth', 'verified']);

    Route::get('/destinos/{viaje_id}', [ViajesController::class, 'getDestinosViaje'])
      ->middleware(['auth', 'verified'])
      ->name('.destinos');

    Route::get('/profesores/count/{viaje_id}', [ViajesController::class, 'countProfesoresViajes'])
      ->middleware(['auth', 'verified'])
      ->name('.profesores.count');
  });
