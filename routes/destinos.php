<?php

use App\Http\Controllers\DestinosController;

Route::prefix('destinos')
  ->name('destinos')
  ->group(function () {
    Route::get('/', [DestinosController::class, 'renderDestinos'])->middleware(['auth', 'verified']);
  });
