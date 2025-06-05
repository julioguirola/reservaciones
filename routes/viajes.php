<?php

use App\Http\Controllers\ViajesController;

Route::prefix('viajes')
  ->name('viajes')
  ->group(function () {
    Route::get('/', [ViajesController::class, 'renderViajes'])->middleware(['auth', 'verified']);
    Route::get('/data', [ViajesController::class, 'getViajes'])
      ->middleware(['auth', 'verified'])
      ->name('.data');
    Route::get('/{viaje_id}', [ViajesController::class, 'infoViaje'])
      ->middleware(['auth', 'verified'])
      ->name('.info');
    Route::get('/{viaje_id}/profesores', [ViajesController::class, 'getProfesoresViaje'])
      ->middleware(['auth', 'verified'])
      ->name('.profesores');
    Route::delete('/{viaje_id}/profesores', [ViajesController::class, 'removeProfesorViaje'])
      ->middleware(['auth', 'verified'])
      ->name('.removeProfesor');
    Route::post('/{viaje_id}/profesores', [ViajesController::class, 'addProfesorViaje'])
      ->middleware(['auth', 'verified'])
      ->name('.addProfesor');
  });
