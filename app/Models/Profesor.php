<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Profesor extends Model
{
  use HasFactory;
  protected $table = 'profesor';
  public $timestamps = false;
  /**
   * @return BelongsTo<Persona,Profesor>
   */
  public function persona(): BelongsTo
  {
    return $this->belongsTo(Persona::class);
  }
  /**
   * @return BelongsTo<Asignatura,Profesor>
   */
  public function asignatura(): BelongsTo
  {
    return $this->belongsTo(Asignatura::class);
  }
  /**
   * @return BelongsTo<Destino,Profesor>
   */
  public function destino(): BelongsTo
  {
    return $this->belongsTo(Destino::class);
  }
  /**
   * @return BelongsTo<Facultad,Profesor>
   */
  public function facultad(): BelongsTo
  {
    return $this->belongsTo(Facultad::class);
  }
  /**
   * @return BelongsToMany<Viaje,Profesor,Pivot>
   */
  public function viajes(): BelongsToMany
  {
    return $this->belongsToMany(Viaje::class, 'viaje_profesor', 'viaje_id', 'profesor_id');
  }
}
