<?php

use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    public function run()
    {
        factory(\App\Book::class)->create(['author_id' => 1]);
        factory(\App\Book::class)->create(['author_id' => 2]);
        factory(\App\Book::class)->create(['author_id' => 3]);
    }
}