<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvitationSubCategory extends Model
{
    protected $fillable = [
        'invitation_category_id',
        'name',
        'slug',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the parent invitation category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(InvitationCategory::class, 'invitation_category_id');
    }
}
