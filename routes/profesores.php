<?php

use App\Http\Controllers\ProfesoresController;

Route::prefix('profesores')
  ->name('profesores')
  ->group(function () {
    Route::get('/', [ProfesoresController::class, 'renderProfesores'])->middleware(['auth', 'verified']);
  });
