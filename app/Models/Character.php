<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $fillable = [
        'character_id',
        'name',
        'description',
        'thumbnail_path',
        'thumbnail_extension',
    ];
}
