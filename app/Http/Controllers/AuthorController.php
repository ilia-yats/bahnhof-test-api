<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Author;
use App\Book;
use App\Resources\AuthorCollection;
use App\Resources\Author as AuthorResource;
use App\Resources\BookCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Validation\ValidationException;

class AuthorController extends RestController {

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse|JsonResource
     */
    public function books(Request $request, int $id)
    {
        try {
            // Check if author exists
            Author::query()->findOrFail($id);

            $this->validate($request, [
                'offset' => 'nullable|numeric',
                'limit' => 'nullable|numeric',
            ]);

            $limit = (int) $request->get('limit', 100);
            $offset = (int) $request->get('offset', 0);

            $books = Book::query()
                ->with('author')
                ->where('author_id', '=', $id)
                ->offset($offset)
                ->limit($limit)
                ->get();

            $totalCount = Book::query()
                ->where('author_id', '=', $id)
                ->count();

            return $this->respondResource(new BookCollection($books, $limit, $offset, $totalCount));

        } catch (ValidationException $e) {

            return $this->respondBadRequest();
        } catch (ModelNotFoundException $e) {

            return $this->respondNotFound();
        }
    }

    protected function getResourcesList(int $limit, int $offset): ResourceCollection
    {
        $authors = Author::query()
            ->offset($offset)
            ->limit($limit)
            ->get();

        $totalCount = Author::query()->count();

        return new AuthorCollection($authors, $limit, $offset, $totalCount);
    }

    protected function getResource(int $id)
    {
        return new AuthorResource(Author::query()->findOrFail($id));
    }

    protected function insertModel(Request $request): void
    {
        $this->validate($request, Author::rules(), Author::messages());

        Author::query()->create($request->post());
    }

    protected function updateModel(int $id, Request $request): void
    {
        $this->validate($request, Author::rulesForUpdate(), Author::messages());

        Author::query()->findOrFail($id)->update($request->all());
    }

    protected function deleteModel(int $id): void
    {
        Author::query()->findOrFail($id)->delete();
    }
}
