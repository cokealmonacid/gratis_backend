<?php
/**
 * Created by PhpStorm.
 * User: entropia
 * Date: 9/20/18
 * Time: 4:11 PM
 */

namespace App\Models;
use Illuminate\Support\Str;



trait AutoGenerateUuid
{

    public static function boot ()
    {
        parent::boot();

        static::creating (function ($model){
            $model->{$model->getKeyName()} = Str::uuid();
        });
    }



}