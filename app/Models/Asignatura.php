<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperAsignatura
 */
class Asignatura extends Model
{
  protected $table = 'asignatura';
  protected $primaryKey = 'id';
  public string $nombre;
  /**
   * @return HasMany<Profesor,Asignatura>
   */
  public function profesores(): HasMany
  {
    return $this->hasMany(Profesor::class);
  }
}
