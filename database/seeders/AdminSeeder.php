<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name'     => 'Super Admin',
            'username' => 'admin',
            'email'    => 'tr.badhan@gmail.com',
            'password' => Hash::make('12345678'), // change this later
            'role'     => 'superadmin',
        ]);
    }
}
