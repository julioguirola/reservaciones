<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Chofer;
use App\Models\Persona;
use App\Models\Profesor;
use App\Models\Viaje;
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

    // User::factory()->create([
    //     'name' => 'Test User',
    //     'email' => 'test@example.com',
    // ]);
    DB::statement('PRAGMA foreign_keys=OFF;');
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

    DB::table('facultad_asignatura')->insert([
      ['facultad_id' => 1, 'asignatura_id' => 3],
      ['facultad_id' => 1, 'asignatura_id' => 4],
      ['facultad_id' => 1, 'asignatura_id' => 6],
      ['facultad_id' => 1, 'asignatura_id' => 11],

      ['facultad_id' => 2, 'asignatura_id' => 5],
      ['facultad_id' => 2, 'asignatura_id' => 7],
      ['facultad_id' => 2, 'asignatura_id' => 8],
      ['facultad_id' => 2, 'asignatura_id' => 9],
      ['facultad_id' => 2, 'asignatura_id' => 10],
      ['facultad_id' => 2, 'asignatura_id' => 12],

      ['facultad_id' => 3, 'asignatura_id' => 5],
      ['facultad_id' => 3, 'asignatura_id' => 6],
      ['facultad_id' => 3, 'asignatura_id' => 7],
      ['facultad_id' => 3, 'asignatura_id' => 8],
      ['facultad_id' => 3, 'asignatura_id' => 12],

      ['facultad_id' => 4, 'asignatura_id' => 1],
      ['facultad_id' => 4, 'asignatura_id' => 2],
      ['facultad_id' => 4, 'asignatura_id' => 3],
      ['facultad_id' => 4, 'asignatura_id' => 11],

      ['facultad_id' => 5, 'asignatura_id' => 1],
      ['facultad_id' => 5, 'asignatura_id' => 2],
      ['facultad_id' => 5, 'asignatura_id' => 3],
      ['facultad_id' => 5, 'asignatura_id' => 11],

      ['facultad_id' => 6, 'asignatura_id' => 3],
      ['facultad_id' => 6, 'asignatura_id' => 2],
      ['facultad_id' => 6, 'asignatura_id' => 6],
      ['facultad_id' => 6, 'asignatura_id' => 11],

      ['facultad_id' => 7, 'asignatura_id' => 1],
      ['facultad_id' => 7, 'asignatura_id' => 2],
      ['facultad_id' => 7, 'asignatura_id' => 3],
      ['facultad_id' => 7, 'asignatura_id' => 4],
      ['facultad_id' => 7, 'asignatura_id' => 6],

      ['facultad_id' => 8, 'asignatura_id' => 3],
      ['facultad_id' => 8, 'asignatura_id' => 4],
      ['facultad_id' => 8, 'asignatura_id' => 11],
      ['facultad_id' => 8, 'asignatura_id' => 12],
    ]);

    Persona::factory()
      ->has(Chofer::factory()->count(1), 'chofer')
      ->count(5)
      ->create();

    Persona::factory()
      ->has(Profesor::factory()->count(1), 'profesor')
      ->count(48)
      ->create();

    Chofer::all()->each(function ($chofer) {
      Viaje::factory()->create(['chofer_id' => $chofer->persona_id]);
    });

    $viajes = Viaje::all();

    $viajes->each(function ($viaje) {
      $inserts = array_map(function ($p) use ($viaje) {
        return ['viaje_id' => $viaje->id, 'profesor_id' => $p->persona_id];
      }, array_slice(Profesor::all()->all(), rand(1, 10), rand(40, 48)));
      DB::table('viaje_profesor')->insert($inserts);
    });
    DB::statement('PRAGMA foreign_keys=ON;');
  }
}
