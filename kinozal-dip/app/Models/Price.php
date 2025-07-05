<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
  public function hall()
  {
      return $this->belongsTo(Hall::class);
  }
}
