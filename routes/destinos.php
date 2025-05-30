<?php

use App\Http\Controllers\DestinosController;

Route::prefix('destinos')
  ->name('destinos')
  ->group(function () {
    Route::get('/', [DestinosController::class, 'renderDestinos'])->middleware(['auth', 'verified']);
    Route::get('/data', [DestinosController::class, 'getDestinos'])
      ->middleware(['auth', 'verified'])
      ->name('.data');
    Route::patch('/{destino_id}', [DestinosController::class, 'editDestino'])
      ->middleware(['auth', 'verified'])
      ->name('.editar');
  });
