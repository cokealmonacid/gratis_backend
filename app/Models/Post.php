<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Post extends Model
{
    use Notifiable;
    use AutoGenerateUuid;
    public $incrementing = false;

    protected $keyType = 'string';
    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'state_id',
        'provincia_id',
        'title',
        'description',
        'publish_date'
    ];

    public static function rules(){
        return [
            'title'         => 'required|max:255',
            'description'   => 'required|max:255',
            'provincia_id'  => 'required|min:1',
            'tags'          => 'required|array',
            'photos'        => 'required|array'        
        ];
    }
}
