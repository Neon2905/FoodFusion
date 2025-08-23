<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_verified_creator',
        'bio',
        'avatar',
        'social_links',
        'dietary_preferences',
        'measurement_units',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified_creator' => 'boolean',
            'social_links' => 'array',
            'dietary_preferences' => 'array',
        ];
    }

    // Relationships
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function reviews()
    {
        return $this->hasMany(RecipeReview::class);
    }

    // Scopes
    public function scopeCreators($query)
    {
        return $query->whereIn('role', ['creator', 'admin']);
    }

    public function scopeVerifiedCreators($query)
    {
        return $query->where('is_verified_creator', true);
    }

    // Accessors
    public function getIsCreatorAttribute(): bool
    {
        return in_array($this->role, ['creator', 'admin']);
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->role === 'admin';
    }
}
