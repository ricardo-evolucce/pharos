<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'path',
        'type',
    ];

    public function entity()
    {
        return $this->morphTo();
    }
}
