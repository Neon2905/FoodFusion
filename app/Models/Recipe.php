<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RecipeStep;
use App\Models\Ingredient;
use App\Models\Nutrition;
use Illuminate\Support\Str;


class Recipe extends Model
{
    /** @use HasFactory<\Database\Factories\RecipeFactory> */
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'slug',
        'title',
        'description',
        'hero_url',
        'prep_time',
        'cook_time',
        'total_time',
        'servings',
        'cuisine',
        'meal_type',
        'difficulty',
        'visibility',
        'rating',
        'analytics_views',
    ];

    protected $casts = [
        // 'comments_enabled' => 'boolean',
        'analytics_views' => 'integer',
        'created_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($recipe) {
            // TODO: Optimize this
            if (empty($recipe->slug) && !empty($recipe->title)) {
                $baseSlug = Str::slug($recipe->title);
                $slug = $baseSlug;
                $i = 1;
                while (self::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $i++;
                }
                $recipe->slug = $slug;
            }
        });
    }

    // Relations
    public function author()
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function steps()
    {
        return $this->hasMany(RecipeStep::class);
    }

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function nutrition()
    {
        return $this->hasOne(Nutrition::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function tips()
    {
        return $this->hasMany(Tip::class);
    }
}
