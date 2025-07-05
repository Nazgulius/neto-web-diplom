<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
  public function sessions()
  {
      return $this->hasMany(KinoSession::class);
  }
}
