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

    public static function getDetailPost($post_id) {
        $post_details = Post::whereId($post_id)
            ->first()
            ->join('users','users.id', '=' ,'posts.user_id')
            ->select(
                'users.id as user_id'
                ,'users.name as user_name'
                ,'users.phone as user_phone'
                ,'users.avatar as user_avatar'
                ,'users.email as user_email'
                ,'posts.id as post_id'
                ,'posts.title as post_title'
                ,'posts.description as post_description'

            )
            ->first();

        return $post_details;

    }


    public static function rules(){
        return [
            'title'         => 'required|max:255',
            'description'   => 'required|max:255',
            'provincia_id'  => 'required|min:1',
            'tags'          => 'required|array',
            'photos'        => 'required|array'
        ];
    }
    public static function rulesGetFilter(){
        return [
            'title'         => 'nullable|max:255',
            'region_id'   => 'nullable|numeric',
            'provincia_id'  => 'nullable|numeric',
            'tag_id'          => 'nullable|numeric',
            'page'          => 'nullable|numeric',

        ];
    }
}
