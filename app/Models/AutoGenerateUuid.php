<?php

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