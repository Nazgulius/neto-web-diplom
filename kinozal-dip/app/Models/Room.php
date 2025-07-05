<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
  public function hall()
  {
      return $this->belongsTo(Hall::class);
  }

  public function seats()
  {
      return $this->hasMany(Seat::class);
  }
}
