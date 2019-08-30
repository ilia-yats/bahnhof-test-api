<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Author
 * @package App
 *
 * @method \Illuminate\Database\Eloquent\Builder query
 */
class Author extends Model {

    use WithRowIdTrait, SoftDeletes;

    protected $primaryKey = 'rowid';

    protected $fillable = ['name'];

    public static function rules()
    {
        return [
            "name" => "required|string",
        ];
    }

    public static function rulesForUpdate()
    {
        return [
            "name" => "sometimes|required|string",
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'A name is required',
        ];
    }

    public function book()
    {
        return $this->hasMany("App\Book", 'author_id', 'rowid')->select('rowid', '*');
    }


}
