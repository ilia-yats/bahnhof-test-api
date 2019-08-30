<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;

class BooksEndpointTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed', ['--class' => 'AuthorsTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BooksTableSeeder']);
    }

    public function testBooksList()
    {
        $this->get('/books')
            ->seeJson([
                "status" => "OK",
                "message" => "OK",
            ])->seeJsonStructure([
                "data" => [
                    'books' => [
                        '*' => array_keys((new \App\Book())->toArray()),
                    ],
                    'limit',
                    'offset',
                    'rows',
                ]
            ]);

    }

    public function testBookCreate()
    {
        $newTitle = 'New testing book';

        /** @var App\Book $book */
        $book = factory(\App\Book::class)->make([
            'title' => $newTitle,
            'author_id' => 1
        ]);

        $this->json('POST', '/books', $book->toArray())
            ->seeJson([
                "status" =>  "OK",
                "message" =>  "OK",
            ])->seeInDatabase('books', ['title' => $newTitle])
            ->assertResponseStatus(201);
    }

    public function testBookUpdate()
    {
        $bookIdToUpdate = 1;
        $newDescription = 'New testing description';
        $newAuthorId = 3;

        $this->json('PUT', "/books/$bookIdToUpdate", [
            'description' => $newDescription,
            'author_id' => $newAuthorId,
        ])->seeJson([
            "status" =>  "OK",
            "message" =>  "OK",
        ])->seeInDatabase('books', [
            'rowid' => $bookIdToUpdate,
            'author_id' => $newAuthorId,
            'description' => $newDescription,
        ])->assertResponseStatus(200);
    }

    public function testBookRemove()
    {
        $bookIdToDelete = 1;

        $this->get("/books/$bookIdToDelete")
            ->assertResponseStatus(200);

        $this->delete("/books/$bookIdToDelete")
            ->assertResponseStatus(204);

        $this->get("/books/$bookIdToDelete")
            ->assertResponseStatus(404);
    }

}