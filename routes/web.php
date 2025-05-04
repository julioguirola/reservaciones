<?php

use App\Http\Controllers\ViajesController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
  return Inertia::render('Welcome');
})
  ->middleware(['auth', 'verified'])
  ->name('home');

Route::get('viajes', function () {
  return Inertia::render('Viajes', ['viajes' => ViajesController::getViajes()]);
})
  ->middleware(['auth', 'verified'])
  ->name('viajes');

Route::get('dashboard', function () {
  return Inertia::render('Dashboard');
})
  ->middleware(['auth', 'verified'])
  ->name('dashboard');

Route::get('destinos', function () {
  return Inertia::render('Destinos');
})
  ->middleware(['auth', 'verified'])
  ->name('destinos');

Route::get('choferes', function () {
  return Inertia::render('Choferes');
})
  ->middleware(['auth', 'verified'])
  ->name('choferes');

Route::get('profesores', function () {
  return Inertia::render('Profesores');
})
  ->middleware(['auth', 'verified'])
  ->name('profesores');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
