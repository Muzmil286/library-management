<?php

namespace Database\Seeders;

use App\Models\Category;
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
        $data = [
            ['name' => 'Philosophy', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'History', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Peotry', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fiction', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Non-Fiction', 'created_at' => now(), 'updated_at' => now()],


        ];
        DB::table('categories')->insert($data);
    }
}
