<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = DB::table('users')->where('id', 1)->first();

        if (!$admin) {
            DB::table('users')->insert([
                'username' => "eduardok",
                'steam_username' => "",
                'email' => "edu@mail.com",
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'role_id' => 1,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
        }

        var_dump($admin);
    }
}
