<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
  public function session()
  {
      return $this->belongsTo(KinoSession::class);
  }

  public function seat()
  {
      return $this->belongsTo(Seat::class);
  }
}
