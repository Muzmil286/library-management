<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['first_name' => 'Mubarak', 'last_name' => 'Qazi', 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Munir', 'last_name' => 'Momin', 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Rupi', 'last_name' => 'Kaur', 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Hellen', 'last_name' => 'Hoover', 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Will', 'last_name' => 'Durant', 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Kahlil', 'last_name' => 'Gibran', 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Friedrich', 'last_name' => 'Niezsche', 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Arthur', 'last_name' => 'Schopenhauer', 'created_at' => now(), 'updated_at' => now()],
            ['first_name' => 'Fyodor', 'last_name' => 'Dostoevsky', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('authors')->insert($data);
    }
}
