<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Record;
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


        return view('charts.getting_started', compact('totalProtein', 'totalFat', 'totalCarbohydrate','formattedDate'));
    }

}
