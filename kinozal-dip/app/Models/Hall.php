<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
  
}
