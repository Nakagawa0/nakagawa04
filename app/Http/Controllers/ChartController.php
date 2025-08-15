<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Record;
use App\Models\Recipe;
use App\Models\UserIngredient;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function getting_started()
    {
        $userId = Auth::id();
        $today = Carbon::today()->locale('ja');

        // 登録したレコードを取得
        $records = Record::where('user_id', $userId)
            ->where('meal_date', $today)
            ->with('recipe')
            ->get();

        // 合計栄養素を初期化
        $totalProtein = 0;
        $totalFat = 0;
        $totalCarbohydrate = 0;

        // 各レコードの栄養素を合計
        foreach ($records as $record) {
            $totalProtein += $record->recipe->protein;
            $totalFat += $record->recipe->fat;
            $totalCarbohydrate += $record->recipe->carbohydrate;
        }

        $formattedDate = $today->isoFormat('YYYY年MM月DD日');

        // 理想の値
        $ideal = [
            'protein' => ['min' => 13, 'max' => 20],
            'fat' => ['min' => 20, 'max' => 30],
            'carbohydrate' => ['min' => 50, 'max' => 65],
        ];

        // パーセンテージ計算
        $total = $totalProtein + $totalFat + $totalCarbohydrate;
        if ($total > 0) {
            $todayRecord = [
                'protein' => ($totalProtein / $total) * 100,
                'fat' => ($totalFat / $total) * 100,
                'carbohydrate' => ($totalCarbohydrate / $total) * 100,
            ];
        } else {
            $todayRecord = ['protein' => 0, 'fat' => 0, 'carbohydrate' => 0];
        }

        // 不足している栄養素
        $shortages = [];
        foreach ($ideal as $nutrient => $range) {
            if ($todayRecord[$nutrient] < $range['min']) {
                $shortages[] = ucfirst($nutrient) . 'が不足しています';
            }
        }

        // 栄養素ベースのレシピ提案
        $nutrientBasedRecipes = [];
        if (count($shortages) > 0) {
            $shortageNames = array_map(function ($msg) { // 構文を修正
                return str_replace(['が不足しています', ' '], '', $msg); // スペルを修正
            }, $shortages);

            $nutrientBasedRecipesQuery = Recipe::where('is_public', true);
            foreach ($shortageNames as $nutrient) {
                $nutrientBasedRecipesQuery->orWhere($nutrient, '>', 0);
            }
            $nutrientBasedRecipes = $nutrientBasedRecipesQuery->limit(3)->get();
        } else {
            // 不足がない場合は、公開されたレシピの中からカロリー順で提案
            $nutrientBasedRecipes = Recipe::where('is_public', true)->orderByDesc('calorie')->limit(3)->get();
        }

        // 食材マッチ率ベースのレシピ提案
        $userIngredientIds = UserIngredient::where('user_id', $userId)
            ->pluck('ingredient_id')
            ->toArray();

        // 'is_public'がtrueのレシピのみ取得
        $allRecipes = Recipe::where('is_public', true)->with('ingredients')->get();
        $scoredRecipes = [];

        // ループ変数を $recipe に修正し、$recipe->ingredients->pluck() に修正
        foreach ($allRecipes as $recipe) {
            $requiredIds = $recipe->ingredients->pluck('id')->toArray();
            $matchCount = count(array_intersect($userIngredientIds, $requiredIds));
            $totalRequired = count($requiredIds);

            if ($totalRequired > 0) {
                $score = $matchCount / $totalRequired;
                $scoredRecipes[] = ['recipe' => $recipe, 'score' => $score];
            }
        }

        usort($scoredRecipes, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        $ingredientBasedRecipes = array_slice(array_column($scoredRecipes, 'recipe'), 0, 3);

        return view('charts.getting_started', [
            'totalProtein' => $totalProtein,
            'totalFat' => $totalFat,
            'totalCarbohydrate' => $totalCarbohydrate,
            'formattedDate' => $formattedDate,
            'shortages' => $shortages,
            'nutrientBasedRecipes' => $nutrientBasedRecipes,
            'ingredientBasedRecipes' => $ingredientBasedRecipes,
        ]);
    }
}
