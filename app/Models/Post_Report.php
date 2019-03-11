<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post_Report extends Model
{
    protected $table = 'post_report';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id', 'report_id', 'user_id'
    ];
}
