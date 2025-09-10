<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KinoSession extends Model
{
  protected $fillable = [
    'movie_id',
    'hall_id',
    'start_datetime',
  ];
  public function movie()
  {
      return $this->belongsTo(Movie::class);
  }

  public function hall()
  {
      return $this->belongsTo(Hall::class);
  }

  public function tickets()
  {
      return $this->hasMany(Ticket::class);
  }

  // Метод для форматирования времени
  public function getFormattedTimeAttribute()
  {
      return $this->start_datetime->format('H:i');
  }
}
