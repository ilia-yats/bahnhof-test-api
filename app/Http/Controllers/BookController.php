<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Book;
use App\Resources\Book as BookResource;
use App\Resources\BookCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookController extends RestController {

    protected function getResourcesList(int $limit, int $offset): ResourceCollection
    {
        $books = Book::query()
            ->with('author')
            ->offset($offset)
            ->limit($limit)
            ->get();

        $totalCount = Book::query()->count();

        return new BookCollection($books, $limit, $offset, $totalCount);
    }

    protected function getResource(int $id)
    {
        return new BookResource(Book::query()->findOrFail($id));
    }

    protected function insertModel(Request $request): void
    {
        $this->validate($request, Book::rules(), Book::messages());

        Book::query()->create($request->post());
    }

    protected function updateModel(int $id, Request $request): void
    {
        $this->validate($request, Book::rulesForUpdate(), Book::messages());

        Book::query()->findOrFail($id)->update($request->all());
    }

    protected function deleteModel(int $id): void
    {
        Book::query()->findOrFail($id)->delete();
    }
}
