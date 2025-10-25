<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Recipe;

class Media extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
