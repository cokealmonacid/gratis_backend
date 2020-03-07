<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;

use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;


    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'provider_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function rulesForCreate()
    {
        return [
            'name'      => 'required|min:6',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8'
        ];
    }

    public static function rulesForUpdate()
    {
        return [
            'email'     => 'email|unique:users',
            'password'  => 'min:8'
        ];
    }

    public static function facebookRules()
    {
        return [
            'facebookId'    => 'required',
            'name'          => 'required',
            'email'         => 'required|email',
            'avatar'        => 'required|imageable',
            'facebookToken' => 'required',
        ];
    }

    public static function rulesForAvatar()
    {
        return [
            'avatar'    => 'required|imageable',
        ];
    }
}
