<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_Post_Like extends Model
{
    protected $table = 'user_post_likes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'post_id'
    ];
}
