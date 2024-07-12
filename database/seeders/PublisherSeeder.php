<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Kalat Publishers', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Balochi Academy', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Harper Collins', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Penguin Random House', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Simon and Schuster', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Macmillan Publishers', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hachette Book Group', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'John Wiley and Sons', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Merriam-Webster', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Scholastic', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pearson', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hougton Miffin Harcourt', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('publishers')->insert($data);
    }
}
