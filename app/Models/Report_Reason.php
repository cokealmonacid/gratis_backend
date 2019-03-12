<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report_Reason extends Model
{
    protected $table = 'report_reasons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description'
    ];

    public static function rules(){
        return [
            'post_id'         => 'required',
            'report_id'       => 'required',
        ];
    }
}
