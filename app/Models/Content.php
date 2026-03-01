<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = ['title', 'description', 'image', 'type', 'is_active', 'order'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
