<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll_option extends Model
{
    use HasFactory;

    protected $table = 'poll_options';


    public $incrementing = false;

    protected $fillable = [
        'post_id',
        'order',
        'text',
        'image_name',
        'votes',
    ];
}
