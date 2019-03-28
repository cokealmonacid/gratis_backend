<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $fillable = [
    	'email', 'token'
    ];

    public static function rules()
    {
        return [
            'email'    => 'required|email'
        ];
    }

    public static function rulesForResetPass()
    {
        return [
            'email'    => 'required|string|email',
            'password' => 'required|min:8',
            'token'    => 'required|string'
        ];
    }
}
