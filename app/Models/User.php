<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable;
    use AutoGenerateUuid;

    public $incrementing = false;

    protected $table = 'users';

    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'social_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function rulesForCreate() {
        return (object) array (
            'rules' => [
            'email' => 'required|email|unique:users',
            'password'  => 'required|min:8'
              ],
            'messages'=> [
                'email.required'=>'No puede dejar el campo email vacio',
                'email.email'=>'No es un email valido',
                'email.unique'=>'El correo ya se encuentra registrado',
                'password.required'=>'No puede dejar el campo password vacio',
                'password.min'=>'La contraseña debe tener un minimo de :min caracteres'

            ]);
    }
}
