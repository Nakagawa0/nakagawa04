<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;
    protected $fillable = [
        'recipe_id',
        'user_id',
        'meal_date',
    ];
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
