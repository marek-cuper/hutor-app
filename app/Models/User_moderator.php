<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_moderator extends Model
{
    use HasFactory;

    protected $table = 'user_moderator';

    protected $fillable = [
        'user_id',
        'admin'
    ];
}
