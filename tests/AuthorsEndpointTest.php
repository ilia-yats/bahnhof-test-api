<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;

class AuthorsEndpointTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed', ['--class' => 'AuthorsTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BooksTableSeeder']);
    }

    public function testAuthorBooks()
    {
        $authorId = 1;

        $this->get("/authors/$authorId/books")
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

        // Check if all returned books belong to requested author
        $books = json_decode($this->response->getContent(), true)['data']['books'];
        $booksOfAuthor = array_filter($books, function(array $bookData) use ($authorId) {
            return $bookData['author']['id'] === $authorId;
        });

        $this->assertEquals(count($books), count($booksOfAuthor));
    }

    public function testAuthorsList()
    {
        $this->get('/authors')
            ->seeJson([
                "status" => "OK",
                "message" => "OK",
            ])->seeJsonStructure([
                "data" => [
                    'authors' => [
                        '*' => array_keys((new \App\Author())->toArray()),
                    ],
                    'limit',
                    'offset',
                    'rows',
                ]
            ]);

    }

    public function testAuthorCreate()
    {
        $newName = 'New testing author';

        /** @var App\Book $author */
        $author = factory(\App\Book::class)->make([
            'name' => $newName,
        ]);

        $this->json('POST', '/authors', $author->toArray())
            ->seeJson([
                "status" =>  "OK",
                "message" =>  "OK",
            ])->seeInDatabase('authors', ['name' => $newName])
            ->assertResponseStatus(201);
    }

    public function testAuthorUpdate()
    {
        $authorIdToUpdate = 1;
        $newName = 'New testing author';

        $this->json('PUT', "/authors/$authorIdToUpdate", [
            'name' => $newName,
        ])->seeJson([
            "status" =>  "OK",
            "message" =>  "OK",
        ])->seeInDatabase('authors', [
            'rowid' => $authorIdToUpdate,
            'name' => $newName,
        ])->assertResponseStatus(200);
    }

    public function testAuthorRemove()
    {
        $authorIdToDelete = 1;

        $this->get("/authors/$authorIdToDelete")
            ->assertResponseStatus(200);

        $this->delete("/authors/$authorIdToDelete")
            ->assertResponseStatus(204);

        $this->get("/authors/$authorIdToDelete")
            ->assertResponseStatus(404);
    }

}