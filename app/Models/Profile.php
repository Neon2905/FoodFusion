<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Media;

class Profile extends Model
{
    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'username',
        'bio',
        'profile',
        'social_links',
        'follower_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'social_links' => 'array',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class);
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function average_rating()
    {
        return $this->recipes()->avg('rating');
    }

    public function videos()
    {
        return $this->hasManyThrough(
            Media::class,
            Recipe::class,
            'profile_id',
            'recipe_id',
            'id',
            'id'
        )->where('type', 'video');
    }
}
