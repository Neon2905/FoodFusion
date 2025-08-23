<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'aliases',
        'nutrition_per_100g',
        'category',
        'is_common',
    ];

    protected $casts = [
        'aliases' => 'array',
        'nutrition_per_100g' => 'array',
        'is_common' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($ingredient) {
            if (empty($ingredient->slug)) {
                $ingredient->slug = Str::slug($ingredient->name);
            }
        });
    }

    // Relationships
    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'recipe_ingredients')
                    ->withPivot(['group_id', 'text', 'quantity', 'unit', 'optional', 'notes', 'order']);
    }

    // Scopes
    public function scopeCommon($query)
    {
        return $query->where('is_common', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
