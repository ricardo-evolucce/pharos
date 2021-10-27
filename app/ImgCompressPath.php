<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImgCompressPath extends Model
{
    protected $fillable = [
        'user_id',
        'url_compress',
        'img_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
