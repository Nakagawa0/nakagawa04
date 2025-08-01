<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'normalized_name',
        'protein',
        'fat',
        'carbohydrate',
    ];

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_ingredient')
                    ->withPivot('quantity');
    }

    public static function normalizeName(string $name): string
    {
        $normalized = trim($name);
        $normalized = mb_convert_kana($normalized, 's');
        $normalized = mb_convert_kana($normalized, 'k'); // ★mb_convert_kanaのエラーを回避
        $normalized = mb_convert_kana($normalized, 'a');
        $normalized = mb_strtolower($normalized);

        return $normalized;
    }
}
