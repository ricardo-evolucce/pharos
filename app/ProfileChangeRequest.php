<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileChangeRequest extends Model
{
    protected $fillable = [
        'changes',
        'profile_id',
    ];

    protected $casts = [
        'changes' => 'array',
    ];
}
