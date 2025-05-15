<?php

use App\Http\Controllers\ProfesoresController;

Route::prefix('profesores')
  ->name('profesores')
  ->group(function () {
    Route::get('/', [ProfesoresController::class, 'renderProfesores'])->middleware(['auth', 'verified']);
    Route::delete('/{profesor_id}', [ProfesoresController::class, 'deleteProfesor'])
      ->middleware(['auth', 'verified'])
      ->name('.eliminar');
    Route::put('/{profesor_id}', [ProfesoresController::class, 'editProfesor'])
      ->middleware(['auth', 'verified'])
      ->name('.editar');
    Route::post('/', [ProfesoresController::class, 'crearProfesor'])
      ->middleware(['auth', 'verified'])
      ->name('.crear');
    Route::get('/asignaturas', [ProfesoresController::class, 'getAsignaturas'])
      ->middleware(['auth', 'verified'])
      ->name('.asignaturas');
    Route::get('/facultades', [ProfesoresController::class, 'getFacultades'])
      ->middleware(['auth', 'verified'])
      ->name('.facultades');
    Route::get('/{profesor_id}', [ProfesoresController::class, 'infoProfesor'])
      ->middleware(['auth', 'verified'])
      ->name('.info');
    Route::get('/{profesor_id}/viajes', [ProfesoresController::class, 'getViajesProfesor'])
      ->middleware(['auth', 'verified'])
      ->name('.viajes');
  });
