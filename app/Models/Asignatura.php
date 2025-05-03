<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Asignatura extends Model
{
  protected $table = 'asignatura';
  protected $primaryKey = 'id';
  public string $nombre;
  /**
   * @return HasMany<Profesor,Asignatura>
   */
  public function profesor(): HasMany
  {
    return $this->hasMany(Profesor::class);
  }
  /**
   * @return BelongsToMany<Facultad,Asignatura,Pivot>
   */
  public function facultades(): BelongsToMany
  {
    return $this->belongsToMany(Facultad::class, 'facultad_asignaturas');
  }
}
