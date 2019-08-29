<?php


namespace App;


trait WithRowIdTrait
{
    public static function query()
    {
        return (new static)->newQuery()
            ->select('rowid', '*'); // is needed to retrieve SQLite rowid
    }
}