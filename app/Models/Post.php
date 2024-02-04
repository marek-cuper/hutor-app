<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'up_votes',
        'down_votes',
        'watched',
        'openned',
        'creator_id',
        'title',
        'text',
        'poll_text'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function regions()
    {
        return $this->belongsToMany(Region::class);
    }

}
