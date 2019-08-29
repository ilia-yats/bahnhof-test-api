<?php

namespace App\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class BookCollection extends ResourceCollection
{
    const DEFAULT_PAGE_SIZE = 2;

    use PaginatedCollectionTrait;

    public function toArray($request)
    {
        return [
            'books' => $this->collection,
            'limit' => $this->limit,
            'offset' => $this->offset,
            'rows' => $this->totalCount,
        ];
    }
}