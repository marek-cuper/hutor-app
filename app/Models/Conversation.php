<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $table = 'conversations';

    protected $fillable = [
        'user1_id',
        'user2_id',
        'user1_openned',
        'user2_openned',
        'last_message_sent_at'
    ];
}
