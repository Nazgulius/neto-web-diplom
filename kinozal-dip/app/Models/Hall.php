<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hall extends Model
{
  protected $fillable = [
    'name',
    'rows',
    'seats_per_row',
    'amountStandart',
    'amountVip',
    'active',
    'vip_count',
  ];

  protected $casts = [
    'layout' => 'json',
  ];

  public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
        // return $this->hasMany(Seat::class)->orderBy('row')->orderBy('number');
    }
  
}
