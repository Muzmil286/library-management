<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Balochi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'English', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sindhi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Urdu', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Arabic', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Persian', 'created_at' => now(), 'updated_at' => now()]
        ];
        DB::table('languages')->insert($data);
    }
}
