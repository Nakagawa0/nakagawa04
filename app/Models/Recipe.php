<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ingredient;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'calorie',
        'instruction',
        'protein',
        'fat',
        'carbohydrate',
    ];

    public function ingredients()
    {
        return $this->belongsTomany(Ingredient::class, 'recipe_ingredient')
                    ->withPivot('quantity');    
    }
}
