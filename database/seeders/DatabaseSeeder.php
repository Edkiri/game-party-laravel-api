<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([RoleSeeder::class]);
        $this->call([AdminSeeder::class]);

        User::factory(10)->create();
    }
}
