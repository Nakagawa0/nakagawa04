<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuggestedRecipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'instrucrtion',
        'protein',
        'fat',
        'carbohydrate',
        'calorie'
    ];
}
