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
            'content'         => 'required|imageable',
            'extension'       => 'required',
            'principal'       => 'required',        
        ];
    }

    public static function createThumbnail($image){
        $originalImage = base64_decode($image);
        $encodedImage  = (string) \Image::make($originalImage)->resize(180, 180)->encode('data-url');
        $thumbnail     = explode(",", $encodedImage)[1];
        return $thumbnail;
    }
}
