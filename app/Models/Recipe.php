<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'subtitle',
        'slug',
        'description',
        'user_id',
        'images',
        'hero_video',
        'prep_time',
        'cook_time',
        'total_time',
        'servings',
        'nutrition',
        'tags',
        'cuisine',
        'meal_type',
        'difficulty',
        'rating_avg',
        'rating_count',
        'comments_enabled',
        'published_at',
        'visibility',
        'language',
        'related_recipes',
        'schema_org',
        'metadata',
        'moderation_status',
        'analytics',
    ];

    protected $casts = [
        'images' => 'array',
        'hero_video' => 'array',
        'nutrition' => 'array',
        'tags' => 'array',
        'meal_type' => 'array',
        'related_recipes' => 'array',
        'schema_org' => 'array',
        'metadata' => 'array',
        'analytics' => 'array',
        'published_at' => 'datetime',
        'comments_enabled' => 'boolean',
        'rating_avg' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($recipe) {
            if (empty($recipe->uuid)) {
                $recipe->uuid = Str::uuid();
            }
            if (empty($recipe->slug)) {
                $recipe->slug = Str::slug($recipe->title);
            }
            // Auto-calculate total time
            $recipe->total_time = ($recipe->prep_time ?? 0) + ($recipe->cook_time ?? 0);
        });

        static::updating(function ($recipe) {
            if ($recipe->isDirty(['prep_time', 'cook_time'])) {
                $recipe->total_time = ($recipe->prep_time ?? 0) + ($recipe->cook_time ?? 0);
            }
        });
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(RecipeStep::class)->orderBy('order');
    }

    public function recipeIngredients(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class)->orderBy('order');
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')
                    ->withPivot(['group_id', 'text', 'quantity', 'unit', 'optional', 'notes', 'order'])
                    ->orderByPivot('order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(RecipeReview::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                    ->where('visibility', 'public')
                    ->where('moderation_status', 'approved');
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    public function scopeByCuisine($query, $cuisine)
    {
        return $query->where('cuisine', $cuisine);
    }

    // Accessors
    public function getIsPublishedAttribute(): bool
    {
        return $this->published_at !== null && 
               $this->visibility === 'public' && 
               $this->moderation_status === 'approved';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
