<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'user_id',
        'player_id',
        'platform',
        'device_token',
        'token'
    ];
}
