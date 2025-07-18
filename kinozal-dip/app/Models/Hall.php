<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
  public function rooms()
  {
      return $this->hasMany(Room::class);
  }
}
