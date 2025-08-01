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

    public function store(Request $request)
    {
        // 入力値のバリデーション
        $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name',
            'protein' => 'required|numeric|min:0',
            'fat' => 'required|numeric|min:0',
            'carbohydrate' => 'required|numeric|min:0',
        ]);

        // 入力された名前を正規化
        $normalizedName = Ingredient::normalizeName($request->input('name'));

        // 正規化された名前で類似食材が既に存在するかチェック
        $exists = Ingredient::where('normalized_name', $normalizedName)->exists();
        if ($exists) {
            return back()->withErrors(['name' => '同じような食材が既に存在します。'])->withInput();
        }

        // 全ての問題がなければ、新しい食材をデータベースに保存
        Ingredient::create([
            'name' => $request->input('name'),
            'normalized_name' => $normalizedName, // ★ここが重要
            'protein' => $request->input('protein'),
            'fat' => $request->input('fat'),
            'carbohydrate' => $request->input('carbohydrate'),
        ]);

        return redirect()->route('ingredients.create')->with('success', '食材を登録しました');
    }
}