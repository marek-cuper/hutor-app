<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_poll_vote extends Model
{
    use HasFactory;

    protected $table = 'user_poll_vote';

    protected $fillable = [
        'user_id',
        'post_id',
        'option_number',
    ];
}
