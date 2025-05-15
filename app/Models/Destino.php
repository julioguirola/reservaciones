<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperDestino
 */
class Destino extends Model
{
  protected $table = 'destino';
  protected $primaryKey = 'id';
  public float $precio;
  public $timestamps = false;
  public string $nombre;
  /**
   * @return HasMany<Profesor,Destino>
   */
  public function profesores(): HasMany
  {
    return $this->hasMany(Profesor::class);
  }
}
