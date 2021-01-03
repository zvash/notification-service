<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 * @property string message
 */
class PushNotification extends Model
{
    protected $fillable = [
        'user_id',
        'message'
    ];
}
