<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperProfesor
 */
class Profesor extends Model
{
  use HasFactory, HasUuids, SoftDeletes;
  protected $table = 'profesor';
  public $timestamps = false;
  public bool $tarifa;
  protected $fillable = ['persona_id', 'destino_id', 'asignatura_id', 'facultad_id', 'tarifa'];
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
    return $this->belongsToMany(Viaje::class);
  }
}
