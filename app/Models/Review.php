<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;

    protected function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    protected function reviewer()
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    protected static function booted()
    {
        static::created(function ($review) {
            $recipe = $review->recipe;
            $recipe->rating = round($recipe->reviews()->avg('rating'), 1);
            $recipe->save();
        });
    }
}
