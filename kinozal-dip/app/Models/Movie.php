<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
  protected $fillable = [
    'title',
    'description',
    'duration',
    'country',
    'image_url',
  ];
  public function sessions()
  {
      return $this->hasMany(KinoSession::class);
  }
}
