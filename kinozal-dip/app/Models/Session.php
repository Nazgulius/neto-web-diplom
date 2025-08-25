<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
  protected $table = 'sessionsGlobal';
  protected $fillable = ['key', 'value'];

  protected $casts = [
      'value' => 'boolean',
  ];
}
