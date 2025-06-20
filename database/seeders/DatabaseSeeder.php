<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Chofer;
use App\Models\Persona;
use App\Models\Profesor;
use App\Models\Viaje;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // User::factory(10)->create();

    User::factory()->create([
      'name' => 'Julio Guirola',
      'email' => 'julio@mail.com',
      'password' => '12345678',
    ]);
    // DB::statement('PRAGMA foreign_keys=OFF;');
    DB::table('destino')->insert([
      [
        'nombre' => 'Matanzas',
        'precio' => 15.0,
      ],
      [
        'nombre' => 'Cienfuegos',
        'precio' => 34.35,
      ],
      [
        'nombre' => 'Santa Clara',
        'precio' => 36.2,
      ],
      [
        'nombre' => 'Sancti  Spíritus',
        'precio' => 46.7,
      ],
      [
        'nombre' => 'Ciego de Ávila',
        'precio' => 56.7,
      ],
      [
        'nombre' => 'Camaguey',
        'precio' => 71.5,
      ],
      [
        'nombre' => 'Las Tunas',
        'precio' => 88.1,
      ],
      [
        'nombre' => 'Holguín',
        'precio' => 98.4,
      ],
      [
        'nombre' => 'Bayamo',
        'precio' => 101.7,
      ],
      [
        'nombre' => 'Santiago de Cuba',
        'precio' => 115.3,
      ],
      [
        'nombre' => 'Guantánamo',
        'precio' => 122,
      ],
    ]);

    DB::table('asignatura')->insert([
      [
        'nombre' => 'Matematicas',
      ],
      [
        'nombre' => 'Fisica',
      ],
      [
        'nombre' => 'Quimica',
      ],
      [
        'nombre' => 'Biologia',
      ],
      [
        'nombre' => 'Historia',
      ],
      [
        'nombre' => 'Geografia',
      ],
      [
        'nombre' => 'Lengua',
      ],
      [
        'nombre' => 'Literatura',
      ],
      [
        'nombre' => 'Arte',
      ],
      [
        'nombre' => 'Musica',
      ],
      [
        'nombre' => 'Educacion Fisica',
      ],
      [
        'nombre' => 'Ciencias Sociales',
      ],
    ]);

    DB::table('facultad')->insert([
      ['nombre' => 'Facultad de Ciencias Naturales'],
      ['nombre' => 'Facultad de Ciencias Sociales'],
      ['nombre' => 'Facultad de Ciencias Humanas'],
      ['nombre' => 'Facultad de Ciencias Exactas'],
      ['nombre' => 'Facultad de Ciencias Tecnologicas'],
      ['nombre' => 'Facultad de Ciencias Ambientales'],
      ['nombre' => 'Facultad de Ciencias Agrarias'],
      ['nombre' => 'Facultad de Ciencias Medicinales'],
    ]);

    Persona::factory()
      ->has(Chofer::factory()->count(1), 'chofer')
      ->count(10)
      ->create();

    Persona::factory()
      ->has(Profesor::factory()->count(1), 'profesor')
      ->count(200)
      ->create();

    Chofer::all()->each(function ($chofer) {
      Viaje::factory()
        ->count(rand(5, 10))
        ->create(['chofer_id' => $chofer->id]);
    });

    $viajes = Viaje::all();

    $viajes->each(function ($viaje) {
      $base = rand(1, 152);
      $top = rand(0, 1) == 1 ? rand(8, 47) : 48;
      $profesores = array_slice(Profesor::all()->all(), $base, $top);
      $inserts = array_map(function ($p) use ($viaje) {
        return ['viaje_id' => $viaje->id, 'profesor_id' => $p->id];
      }, $profesores);
      DB::table('profesor_viaje')->insert($inserts);
    });

    // DB::statement('PRAGMA foreign_keys=ON;');
  }
}
