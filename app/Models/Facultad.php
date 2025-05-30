<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperFacultad
 */
class Facultad extends Model
{
  protected $table = 'facultad';
  protected $primaryKey = 'id';
  public $nombre;
  /**
   * @return HasMany<Profesor,Facultad>
   */
  public function profesores(): HasMany
  {
    return $this->hasMany(Profesor::class);
  }
}
