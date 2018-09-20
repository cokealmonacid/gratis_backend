<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rol extends Model
{
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
    ];
}
