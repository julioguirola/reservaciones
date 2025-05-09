<?php

use App\Http\Controllers\ChoferesController;

Route::prefix('choferes')
  ->name('choferes')
  ->group(function () {
    Route::get('/', [ChoferesController::class, 'renderChoferes'])->middleware(['auth', 'verified']);
  });
