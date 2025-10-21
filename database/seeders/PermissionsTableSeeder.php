<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('permissions')->insert([
            ['id' => 1, 'name' => 'view', 'slug' => 'view', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'edit', 'slug' => 'edit', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'insert', 'slug' => 'insert', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'delete', 'slug' => 'delete', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
