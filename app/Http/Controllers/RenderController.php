<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class RenderController extends Controller
{
  public static function renderWelcome(): Response
  {
    return Inertia::render('Welcome');
  }

  public static function renderDashboard(): Response
  {
    return Inertia::render('Dashboard');
  }
}
