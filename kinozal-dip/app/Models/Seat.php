<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
  public function room()
  {
      return $this->belongsTo(Room::class);
  }

  public function tickets()
  {
      return $this->hasMany(Ticket::class);
  }
}
