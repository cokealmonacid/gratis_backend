<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_Rol extends Model
{
    protected $table = 'user_roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'rol_id'
    ];
}
