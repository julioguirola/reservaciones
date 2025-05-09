<?php

use App\Http\Controllers\ViajesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ViajesController::class, 'renderViajes'])
  ->middleware(['auth', 'verified'])
  ->name('home');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/viajes.php';
require __DIR__ . '/choferes.php';
require __DIR__ . '/destinos.php';
require __DIR__ . '/profesores.php';
