<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post_Tags extends Model
{
    protected $table = 'post_tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id', 'tag_id',
    ];
}
