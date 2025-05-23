<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPersona
 */
class Persona extends Model
{
  use HasFactory, HasUuids;
  protected $table = 'persona';
  public string $nombre;
  public string $carnet_identidad;
  public $timestamps = false;
  protected $fillable = ['nombre', 'carnet_identidad'];
  /**
   * @return HasOne<Chofer,Persona>
   */
  public function chofer(): HasOne
  {
    return $this->hasOne(Chofer::class);
  }
  /**
   * @return HasOne<Profesor,Persona>
   */
  public function profesor(): HasOne
  {
    return $this->hasOne(Profesor::class);
  }
}
