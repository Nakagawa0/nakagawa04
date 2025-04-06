<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    public function create()
    {
        return view('ingredients.create');
    }
    public function store(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'protein'=>  'required|numeric',
            'fat'=>  'required|numeric',
            'carbohydrate'=> 'required|numeric',
        ]);


        Ingredient::create([
            'name' => $request->name,
            'protein' => $request->protein,
            'fat' => $request->fat,
            'carbohydrate' => $request->carbohydrate,
        ]);

        return redirect()->route('ingredients.create')->with('success', '食材を登録しました');
    }
}
