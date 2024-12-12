<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Book;

class BookshelfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bookshelves')->insert([
            'code' => 'BS001',
            'name' => 'Novel'
        ]);

        Book::create([
            'title' => 'Shawn Garcia',
            'author' => 'Shawn Garcia',
            'year' => '2022',
            'publisher' => 'Shawn Garcia',
            'city' => 'Palu',
            'cover' => '214639-20231011-cover_31.jpg-642x1024.webp',
            'bookshelf_id'=> '1',
        ]);
    }
}
