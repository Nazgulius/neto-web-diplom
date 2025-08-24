<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
  protected $casts = ['is_open' => 'boolean'];
  protected $fillable = ['is_open',];
}
