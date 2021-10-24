<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImgCompressPath extends Model
{
    protected $fillable = [
        'profile_id',
        'url_compress',
        'img_name',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }
}
