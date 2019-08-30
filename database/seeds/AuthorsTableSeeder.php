<?php

use Illuminate\Database\Seeder;

class AuthorsTableSeeder extends Seeder
{
    public function run()
    {
        factory(\App\Author::class)->create(['rowid' => 1]);
        factory(\App\Author::class)->create(['rowid' => 2]);
        factory(\App\Author::class)->create(['rowid' => 3]);
    }
}