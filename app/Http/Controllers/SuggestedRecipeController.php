<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuggestedRecipe;
use App\Models\Ingredient;

class SuggestedRecipeController extends Controller
{
    public function create(){
        //$ingredients = Ingredient::all()
        return view('suggested_recipes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instruction' => 'nullable|string',
            'protein' => 'required|numeric',
            'fat' => 'required|numeric',
            'carbohydrate' => 'required|numeric',
        ]);

        $calorie = round($request->protein * 4 + $request->fat * 9 + $request->carbohydrate * 4);

        $SuggestedRecipe=SuggestedRecipe::create([
            'name' => $request->name,
            'description' => $request->description,
            'instruction' => $request->instruction,
            'protein' => $request->protein,
            'fat' => $request->fat,
            'carbohydrate' => $request->carbohydrate,
            'calorie' => $calorie,
        ]);
        
        if($recipe->calorie > 0){
            $recipe->protein_ratio = ($recipe->protein * 4) / $recipe->calorie;
            $recipe->fat_ratio = ($recipe->fat * 9) / $recipe->calorie;
            $recipe->carbohydrate_ratio = ($recipe->carbohydrate * 4) / $recipe->calorie;
            $recipe->save();
        }
        return redirect()->route('suggested_recipes.create')->with('success', '提案レシピを登録しました');
    }
}
