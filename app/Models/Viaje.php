<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperViaje
 */
class Viaje extends Model
{
  use HasFactory;
  protected $table = 'viaje';
  protected $primaryKey = 'id';
  public DateTime $fecha;
  public $timestamps = false;
  protected $fillable = ['fecha', 'chofer_id'];
  /**
   * @return BelongsTo<Chofer,Viaje>
   */
  public function chofer(): BelongsTo
  {
    return $this->belongsTo(Chofer::class);
  }
  /**
   * @return HasMany<Profesor,Viaje>
   */
  public function profesores(): HasMany
  {
    return $this->hasMany(Profesor::class);
  }
}
