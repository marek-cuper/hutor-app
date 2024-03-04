<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post_comment extends Model
{
    use HasFactory;

    protected $table = 'post_comment';

    protected $fillable = [
        'post_id',
        'user_id',
        'upper_comment_id',
        'order',
        'text',
        'up_votes',
        'down_votes',
    ];
}
