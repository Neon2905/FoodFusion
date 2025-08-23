<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipe_id',
        'user_id',
        'rating',
        'comment',
        'images',
        'is_verified',
    ];

    protected $casts = [
        'images' => 'array',
        'is_verified' => 'boolean',
    ];

    // Relationships
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }
}
