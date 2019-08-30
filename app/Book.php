<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Book
 * @package App
 *
 * @method \Illuminate\Database\Eloquent\Builder query
 */
class Book extends Model {

    use WithRowIdTrait, SoftDeletes;

    protected $primaryKey = 'rowid';

    protected $fillable = ['author_id', 'title', 'description'];

    public static function rules()
    {
        return [
            "author_id" => "required|exists:authors,rowid",
            "title" => "required",
            "description" => "required",
        ];
    }

    public static function rulesForUpdate()
    {
        return [
            "author_id" => "sometimes|required|exists:authors,rowid",
            "title" => "sometimes|required",
            "description" => "sometimes|required",
        ];
    }

    public static function messages()
    {
        return [
            'author_id.required' => 'An author is required',
            'author_id.exists' => 'The author must exist',
            'title.required'  => 'A title is required',
            'description.required'  => 'A description is required',
        ];
    }

    public function author()
    {
        return $this->belongsTo("App\Author", 'author_id', 'rowid')->select('rowid', '*');
    }
}
