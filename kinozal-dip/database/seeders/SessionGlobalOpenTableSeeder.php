<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 
use App\Models\Session;

class SessionGlobalOpenTableSeeder extends Seeder
{
  public function run(): void
  {
    Session::truncate(); 
    Session::create([
      'key' => 'sales_globally_open',
      'value' => true,
    ]);  
  } 
}