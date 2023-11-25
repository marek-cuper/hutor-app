<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'text',
        'image_name',
        'poll_text',
    #    'pool_id',
    #    'pref_id',
    #    'location_id',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

}
