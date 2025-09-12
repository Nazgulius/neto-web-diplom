<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
  public function hall()
  {
    return $this->belongsTo(Hall::class);
  }

  public function tickets()
  {
    return $this->hasMany(Ticket::class);
  }

  public function session()
  {
      return $this->belongsTo(KinoSession::class);
  }

  protected $fillable = [
    'hall_id',
    'session_id',
    'row',
    'number',
    'type',
    'status'
  ];

  // Можно добавить геттеры/сеттеры или константы для статусов
  const STATUS_AVAILABLE = 'available';
  const STATUS_BLOCKED = 'blocked';
  const STATUS_BOOKED = 'booked';

  public function isAvailable()
  {
    return $this->status === self::STATUS_AVAILABLE;
  }

  public function isBlocked()
  {
    return $this->status === self::STATUS_BLOCKED;
  }

  public function isBooked()
  {
    return $this->status === self::STATUS_BOOKED;
  }
}
