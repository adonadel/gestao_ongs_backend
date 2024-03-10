<?php

namespace Database\Seeders;

use App\Models\People;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $person = People::factory()->create([
             'name' => 'Administrador',
             'email' => 'admin@teste.com',
        ]);

         User::factory()->create([
             'password' => Hash::make('123456'),
             'people_id' => $person->id,
         ]);
    }
}
