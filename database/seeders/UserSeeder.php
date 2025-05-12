<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      User::create([
        'name' => 'Daniel',
        'email' => 'daniel@gmail.com',
        'password' => Hash::make('password'),
        'remember_token' => Str::random(10),
        'email_verified_at' => now(),
      ]);

      User::create([
        'name' => 'john',
        'email' => 'john@doe.com',
        'password' => Hash::make('password'),
        'remember_token' => Str::random(10),
        'email_verified_at' => now(),
      ]);

      // User::factory(2)->create();
    }
}
