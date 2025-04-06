<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function create()
    {
        return view('recipes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'calorie' => 'required|integer',
            'instruction' => 'required|string',
            'protein' => 'required|numeric|min:0',
            'carbohydrate' => 'required|numeric|min:0',
        ]);

        // 登録処理
        Recipe::create([
            'name' => $request->name,
            'description' => $request->description,
            'calorie' => $request->calorie,
            'instruction' => $request->instruction,
            'protein' => $request->fat,
            'carbohydrate' => $request->carbohydrate,
        ]);

        return redirect()->route('recipes.create')->with('success', 'レシピを登録しました');
    }
}

