<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AppController extends Controller
{
  public static function renderWelcome()
  {
    return Inertia::render('Welcome');
  }

  public static function renderDashboard()
  {
    return Inertia::render('Dashboard');
  }
}
