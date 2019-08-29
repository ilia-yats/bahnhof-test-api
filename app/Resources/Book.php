<?php


namespace App\Resources;


use Illuminate\Http\Resources\Json\Resource;

class Book extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->rowid,
            'title' => $this->title,
            'author' => new Author($this->whenLoaded('author')),
        ];
    }
}