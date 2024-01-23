<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post_region extends Model
{
    use HasFactory;

    protected $table = 'post_region';

    protected $fillable = [
        'post_id',
        'region_id',
    ];
}
