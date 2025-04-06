<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserIngredient;
use App\Models\Ingredient;

class UserIngredientController extends Controller
{
    public function create()
    {
        $ingredients = Ingredient::all(); // 食材リスト取得
        return view('user_ingredients.create', compact('ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
        ]);

        UserIngredient::create([
            'user_id' => auth()->id(),
            'ingredient_id' => $request->ingredient_id,
            'name' => $request->name,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('user_ingredients.create')->with('success', '食材を登録しました');
    } 
}
