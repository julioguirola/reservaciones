<?php

use App\Http\Controllers\RenderController;
use App\Http\Controllers\ChoferesController;
use App\Http\Controllers\DestinosController;
use App\Http\Controllers\ProfesoresController;
use App\Http\Controllers\ViajesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RenderController::class, 'renderWelcome'])
  ->middleware(['auth', 'verified'])
  ->name('home');

Route::get('viajes', [ViajesController::class, 'renderViajes'])
  ->middleware(['auth', 'verified'])
  ->name('viajes');

Route::get('dashboard', [RenderController::class, 'renderDashboard'])
  ->middleware(['auth', 'verified'])
  ->name('dashboard');

Route::get('destinos', [DestinosController::class, 'renderDestinos'])
  ->middleware(['auth', 'verified'])
  ->name('destinos');

Route::get('choferes', [ChoferesController::class, 'renderChoferes'])
  ->middleware(['auth', 'verified'])
  ->name('choferes');

Route::get('profesores', [ProfesoresController::class, 'renderProfesores'])
  ->middleware(['auth', 'verified'])
  ->name('profesores');

Route::delete('profesores/{profesor_id}', [ProfesoresController::class, 'deleteProfesor'])
  ->middleware(['auth', 'verified'])
  ->name('profesores.eliminar');

Route::get('profesores/count/{viaje_id}', [ProfesoresController::class, 'countProfesoresViajes'])
  ->middleware(['auth', 'verified'])
  ->name('profesores.count');

Route::get('viajes/destinos/{viaje_id}', [DestinosController::class, 'getDestinosViaje'])
  ->middleware(['auth', 'verified'])
  ->name('viajes.destinos');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
