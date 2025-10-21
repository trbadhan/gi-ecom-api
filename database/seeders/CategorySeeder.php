<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Parent 1246789', 'parent_id' => null, 'order' => 1, 'is_active' => 'active', 'created_at' => null, 'updated_at' => '2025-08-28 03:14:24'],
            ['id' => 2, 'name' => 'Child 1 Parent 1', 'parent_id' => 1, 'order' => 2, 'is_active' => 'active', 'created_at' => null, 'updated_at' => '2025-08-28 01:40:40'],
            ['id' => 3, 'name' => 'Child 1 Parent 1', 'parent_id' => 1, 'order' => 1, 'is_active' => 'active', 'created_at' => null, 'updated_at' => '2025-08-28 02:50:04'],
            ['id' => 4, 'name' => 'Abc', 'parent_id' => 5, 'order' => 1, 'is_active' => 'active', 'created_at' => null, 'updated_at' => '2025-08-28 01:35:48'],
            ['id' => 5, 'name' => 'Parent 2', 'parent_id' => null, 'order' => 2, 'is_active' => 'active', 'created_at' => '2025-08-27 04:07:10', 'updated_at' => '2025-08-28 02:49:04'],
            ['id' => 6, 'name' => 'AAC', 'parent_id' => 5, 'order' => 2, 'is_active' => 'active', 'created_at' => '2025-08-27 04:07:24', 'updated_at' => '2025-08-28 01:39:57'],
            ['id' => 7, 'name' => 'Parent 3', 'parent_id' => null, 'order' => 3, 'is_active' => 'active', 'created_at' => '2025-08-28 02:54:05', 'updated_at' => '2025-08-28 02:54:05'],
            ['id' => 8, 'name' => 'Child 1 Parent 3', 'parent_id' => 7, 'order' => 1, 'is_active' => 'active', 'created_at' => '2025-08-28 02:54:28', 'updated_at' => '2025-08-28 02:54:28'],
            ['id' => 9, 'name' => 'Parent 4', 'parent_id' => null, 'order' => 4, 'is_active' => 'active', 'created_at' => '2025-08-28 02:58:58', 'updated_at' => '2025-08-28 02:58:58'],
            ['id' => 10, 'name' => 'Parent 123', 'parent_id' => null, 'order' => 6, 'is_active' => 'active', 'created_at' => '2025-08-28 02:59:25', 'updated_at' => '2025-08-28 02:59:25'],
            ['id' => 11, 'name' => 'Parent 9871', 'parent_id' => null, 'order' => 1, 'is_active' => 'active', 'created_at' => '2025-08-28 03:00:08', 'updated_at' => '2025-08-28 03:14:55'],
            ['id' => 12, 'name' => 'Parent 99999', 'parent_id' => null, 'order' => 7, 'is_active' => 'active', 'created_at' => '2025-08-28 03:15:12', 'updated_at' => '2025-08-28 03:15:12'],
            ['id' => 13, 'name' => 'parent 888', 'parent_id' => null, 'order' => 1, 'is_active' => 'active', 'created_at' => '2025-08-28 03:15:24', 'updated_at' => '2025-08-28 03:15:32'],
            ['id' => 14, 'name' => 'Nothing', 'parent_id' => 12, 'order' => 1, 'is_active' => 'active', 'created_at' => '2025-08-28 03:15:57', 'updated_at' => '2025-08-28 03:15:57'],
        ]);
    }
}
