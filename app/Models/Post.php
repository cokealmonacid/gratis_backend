<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Post extends Model
{
    use Notifiable;
    use AutoGenerateUuid;
    public $incrementing = false;

    protected $keyType = 'string';
    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'state_id',
        'provincia_id',
        'title',
        'description',
        'publish_date'
    ];
}
