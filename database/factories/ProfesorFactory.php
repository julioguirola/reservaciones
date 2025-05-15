<?php

namespace Database\Factories;

use App\Models\Profesor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profesor>
 */
class ProfesorFactory extends Factory
{
  protected $model = Profesor::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'facultad_id' => rand(1, 8),
      'asignatura_id' => rand(1, 12),
      'destino_id' => rand(1, 11),
      'tarifa' => false,
    ];
  }
}
