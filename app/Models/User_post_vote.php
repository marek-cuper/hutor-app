<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_post_vote extends Model
{
    use HasFactory;

    protected $table = 'user_post_vote';

    protected $fillable = [
        'user_id',
        'post_id',
        'up_vote',
    ];
}
