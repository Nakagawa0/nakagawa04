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
        $recommendedRecipes=[];
        if(!empty($shortages)){
            foreach ( $shortages as $shortage) {
                $recommendedRecipes[] = Recipe::orderByDesc($shortage)->limit(3)->get();
            } 
        } else{
            $recommendedRecipes[] = Recipe::orderByDesc("calorie")->limit(3)->get(); 
        }

        return view('charts.getting_started', compact(
            'totalProtein', 'totalFat', 'totalCarbohydrate',
            'formattedDate', 'shortages', 'recommendedRecipes'));
    }

}
