<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvitationProduct extends Model
{
    protected $fillable = ['invitation_category_id', 'name', 'description', 'price', 'thumbnail', 'is_active'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(InvitationCategory::class, 'invitation_category_id');
    }
}
