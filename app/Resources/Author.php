<?php


namespace App\Resources;


use Illuminate\Http\Resources\Json\Resource;

class Author extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->rowid,
            'name' => $this->name,
        ];
    }
}