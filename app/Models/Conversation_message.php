<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation_message extends Model
{
    use HasFactory;

    protected $table = 'conversation_message';

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'text',
    ];
}
