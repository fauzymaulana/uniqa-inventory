<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvitationCategory extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function products(): HasMany
    {
        return $this->hasMany(InvitationProduct::class);
    }

    /**
     * Get all sub-categories for this category.
     */
    public function subCategories(): HasMany
    {
        return $this->hasMany(InvitationSubCategory::class)
            ->where('is_active', true)
            ->orderBy('order', 'asc');
    }
}
