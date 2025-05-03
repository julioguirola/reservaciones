<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destino extends Model
{
  protected $table = 'destino';
  protected $primaryKey = 'id';
  public float $precio;
  public string $nombre;
  /**
   * @return HasMany<Profesor,Destino>
   */
  public function profesor(): HasMany
  {
    return $this->hasMany(Profesor::class);
  }
}
