<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversations extends Model
{
    protected $fillable = [
        'conversation_id', 'user_id', 'message',
    ];
}
