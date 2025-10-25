<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nutrition extends Model
{
    //
    protected $fillable = [
        'recipe_id',
        'calories',
        'protein',
        'carbs',
        'fat',
        'fiber',
        'sugar',
    ];
}
