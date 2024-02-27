<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_comment_vote extends Model
{
    use HasFactory;

    protected $table = 'user_comment_vote';

    protected $fillable = [
        'comment_id',
        'user_id',
        'up_vote',
    ];
}
