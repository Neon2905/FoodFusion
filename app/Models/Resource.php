<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',    // optional author
        'title',
        'slug',
        'category',      // 'culinary' | 'educational'
        'type',          // 'card'|'tutorial'|'video'|'technique'
        'description',
        'file_path',     // local downloadable file (storage/app/public/...)
        'external_url',  // external link
        'thumbnail_url',
        'duration',      // seconds for videos
        'tags',
        'published',
    ];

    protected $casts = [
        'tags' => 'array',
        'published' => 'boolean',
        'duration' => 'integer',
    ];

    public function author()
    {
        return $this->belongsTo(\App\Models\Profile::class, 'profile_id');
    }
}
