<?php


namespace App\Resources;


trait PaginatedCollectionTrait
{
    public $limit;
    public $offset;
    public $totalCount;

    public function __construct($resource, int $limit, int $offset, int $totalCount)
    {
        $this->limit = $limit;
        $this->offset = $offset;
        $this->totalCount = $totalCount;

        parent::__construct($resource);
    }
}