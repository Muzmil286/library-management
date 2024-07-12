<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books =  Book::factory(150)->create();
        $authors = Author::select('id')->get();
        $languages = Language::select('id')->get();
        foreach ($books as $book) {
            $book->authors()->sync($authors);
            $book->languages()->sync($languages);
        }
    }
}
