<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Record;
use App\Models\Recipe;
use App\Models\SuggestedRecipe;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function getting_started(){
        $userId = Auth::id(); //userのIDを取得
        $today = Carbon::today()->locale('ja'); //今日の日付

        // 登録したレシピを取得
        $records = Record::where('user_id', $userId)
        ->where('meal_date', $today)
        ->with('recipe') //リレーション取得
        ->get();
        // 合計
        $totalProtein= 0;
        $totalFat= 0;
        $totalCarbohydrate= 0;
        // 各レコードのレシピから、栄養素を取り出して合計する
        foreach ($records as $record) {
            $totalProtein += $record->recipe->protein;
            $totalFat += $record->recipe->fat;
            $totalCarbohydrate += $record->recipe->carbohydrate;
        }
        $formattedDate = $today->isoFormat('YYYY年MM月DD日');

        // 理想の値
        $ideal = [
            'protein' => ['min' => 13, 'max' =>20],
            'fat' => ['min' => 20, 'max' => 30],
            'carbohydrate' => ['min' => 50, 'max' => 65],
        ];
        
        $total= $totalProtein + $totalFat + $totalCarbohydrate;

        // 今日の値
        $todayRecord = $total>0 ? [
            'protein' =>  ($totalProtein / $total) * 100,
            'fat' => ($totalFat / $total) * 100,
            'carbohydrate' => ($totalCarbohydrate/ $total) * 100,
        ] : [
            // $totalが0のときの除算で発生するエラー防止
            'protein' => 0,
            'fat' => 0,
            'carbohydrate' => 0, 
        ];
        // 不足している栄養素
        $shortages = []; //検索用
        $shortageMessages = []; //表示用

        foreach ($ideal as $nutrient => $range) {
            if ($todayRecord[$nutrient] < $range['min']){
                $shortages[] = $nutrient;
                $shortageMessages[] = ucfirst($nutrient) . 'が不足しています';
            }
        }

        //栄養のバランスをもとにレシピを提案
        $nutirentBasedRecipes=[];
        if(!empty($shortages)){
            foreach ( $shortages as $shortage) {
                $nutirentBasedRecipes[] = Recipe::orderByDesc($shortage)->limit(3)->get();
            } 
        } else{
            $nutirentBasedRecipes[] = Recipe::orderByDesc("calorie")->limit(3)->get(); 
        }

        // 家にある食材ベースのレシピ提案
        $userIngredients = \App\Models\UserIngredient::where('user_id', $userId)->pluck('ingredient_id')-?toArray();
        $allRecipes = Recipe::with('ingredients')->get();
    
        $scoredRecipes = [];
        foreach ($allRecipes as $recipe) {
            $requiredIngredients = $recips->ingredients->pluck('id')->toArray();
            $matchCount = count(array_intersect($userIngredients, $requiredIngredients));
            $totalRequired = count($requireedIngredients);
            if ($totalRequired > 0) {
                $matchRate = $matchCount / $totalRequired;
                $scoredRecipes[] =[
                    'recipes' => $recipe,
                    'score' => $matchRate
                ];
            }

        }
        usort($scoredRecipes, fn($a, $b) => $b['score'] <=> $a['score']);
        $ingredientBasedRecipes = array_slice(array_column($scoredRecipes, 'recipe'), 0, 3);

        

        return view('charts.getting_started', compact(
            'totalProtein', 'totalFat', 'totalCarbohydrate',
            'formattedDate', 'shortages', 'nutirentBasedRecipes',
        'ingredientBasedRecipes'));
    }

}
