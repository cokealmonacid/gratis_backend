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
        'url',
        'filename',
        'principal'
    ];

    public static function rules(){
        return [
            'content'         => 'required|imageable',
            'principal'       => 'required',        
        ];
    }

    public static function createThumbnail($image){
        $originalImage = base64_decode(explode(",", $image)[1]);
        $encodedImage  = (string) \Image::make($originalImage)->resize(180, 180)->encode('data-url');
        return $encodedImage;
    }
}
