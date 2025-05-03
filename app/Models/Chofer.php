<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chofer extends Model
{
  use HasFactory;
  protected $table = 'chofer';
  public string $licencia_numero;
  public $timestamps = false;
  protected $primaryKey = 'persona_id';
  /**
   * @return BelongsTo<Persona,Chofer>
   */
  public function persona(): BelongsTo
  {
    return $this->belongsTo(Persona::class);
  }
  /**
   * @return HasMany<Viaje,Chofer>
   */
  public function viajes(): HasMany
  {
    return $this->hasMany(Viaje::class, 'chofer_id', 'persona_id');
  }
}
