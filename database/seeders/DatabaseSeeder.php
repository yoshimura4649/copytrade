<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    DB::table('admins')->insert([
      'name' => Str::random(10),
      'email' => Str::random(10) . '@admin.app',
      'group' => 100,
      'password' => Hash::make('password'),
    ]);
  }
}
