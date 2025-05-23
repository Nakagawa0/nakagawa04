<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeIngredient extends Model
{
    use HasFactory;
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
    protected $fillable = [
        'recipe_id',
        'weight',
        'ingredient_id',
    ];
}
