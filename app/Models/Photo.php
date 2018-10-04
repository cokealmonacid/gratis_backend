<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Photo extends Model
{
    use Notifiable;
    use AutoGenerateUuid;
    public $incrementing = false;
    
    protected $keyType = 'string';
    protected $table = 'photos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id',
        'image',
        'thumbnail',
        'extension',
        'principal'
    ];

    public static function rules(){
        return [
            'content'         => 'required',
            'extension'       => 'required',
            'principal'       => 'required',        
        ];
    }

    public statis function createThumbnail($image){
        
    }
}
