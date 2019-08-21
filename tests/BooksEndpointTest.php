<?php


class BooksEndpointTest extends TestCase
{
    public function testBooksEndpoint()
    {
        $this->get('/books')
            ->seeJson([
                "status" =>  "OK",
                "message" =>  "Ok"
            ]);
    }
}