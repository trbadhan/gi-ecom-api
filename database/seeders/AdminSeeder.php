<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->insert([
            'id' => 1,
            'name' => 'Super Admin',
            'username' => 'admin',
            'email' => 'tr.badhan@gmail.com',
            'password' => bcrypt('123456'),
            'role' => 'superadmin',
            'remember_token' => null,
            'created_at' => '2025-08-20 04:51:56',
            'updated_at' => '2025-08-20 04:51:56',
        ]);
    }
}
