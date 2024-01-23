<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_region extends Model
{
    use HasFactory;

    protected $table = 'user_region';

    protected $fillable = [
        'user_id',
        'region_id',
    ];
}
