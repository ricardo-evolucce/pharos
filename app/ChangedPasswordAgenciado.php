<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangedPasswordAgenciado extends Model
{
    protected $fillable = [
        //'changes',
        'profile_id',
    ];

    /*protected $casts = [
        'changes' => 'array',
    ];*/
}
