<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Recipe;
use App\Models\Ingredient;




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
            'protein' => $request->protein,
            'fat' => $request->fat,
            'carbohydrate' => $request->carbohydrate,
        ]);

        return redirect()->route('recipes.create')->with('success', 'レシピを登録しました');
    }

    public function nutrient()
    {
        return view('recipes.nutrient');
    }

    public function nutrientSearch(Request $request)
    {
        //受け取った食材名を$foodに入れる
        $food = $request['nutrient'];

        //ingredientsテーブルに同じ名前の食べ物が含まれていれば、そのデータを取得する
        $registeredFood = Ingredient::where('name', $food)->get();

        //同じ食材が登録されないようにする
        if ($food == $registeredFood) {
            // ddは処理を書かれた時点で止めて、データ等を確かめるデバック用の関数
            // ここは本番では処理を書き換えること
            return dd("既に登録済みです。");
        }

        // API呼び出し　ここでは食材の食品番号を検索している
        // $responseにはAPIの結果が入る
        $response = Http::get('https://script.google.com/macros/s/AKfycbzO6IMoPPbtBLb_AnRwgB1OheJyF5XwgNyj28NZdyjg76q4AzX0/exec', [
            //食材名をエンドポイントに含める
            'name' => $food
        ]);

        //APIの検索が失敗した場合
        if (empty($response->json()[0])){
            // return dd("食品番号食材が見つかりませんでした");
            return redirect()->route('recipes.nutrient')->with('error', '食材が見つかりませんでした');
        }

        // APIで呼び出した結果の中から食品番号だけを取り出す
        $result = $response->json()[0];
        
        // $foodの中から食品番号だけを取り出す
        // preg_replace()は文字列を置換するためのPHPの関数　(int)で$foodNumber確実に数字として処理されるように定義
        
        $foodNumber = (int)preg_replace('/\D/', '', $result);

        // これまでの処理で得られた$foodNumber（食品番号）とユーザが入力した食品名である$foodを引数に
        // 食品野栄養素を調べるためにnutrientValue関数を呼び出す
        return $this->nutrientValue($foodNumber, $food);       
    }
    
    // 食材栄養素検索
    public function nutrientValue($foodNumber, $food)
    {
        // API呼び出し
        $response = Http::get('https://script.google.com/macros/s/AKfycbx7WZ-wdIBLqVnCxPwzedIdjhC3CMjhAcV0MufN2gJd-xsO3xw/exec',
        [
            // 引数として渡された$foodNumber (食品番号) と重量(weight) をエンドポイントに追加
            'num' => $foodNumber,
            'weight'=> 100
        ]);

    // APIの検索が失敗した場合
    if(empty($response->json())){
        // return dd($response->json());
        return redirect()->route('recipes.nutrient')->with('error', '食材の栄養素が取得できませんでした');
    }

    // 結果を$nutrientsに取り出してから各栄養素を変数に代入
    $nutrients = $response->json();
    // 保存処理をsaveIngredient関数で行う
    return $this->saveIngredient($nutrients, $food);
    }

    // データ保存処理
    public function saveIngredient($nutrients, $food)
    {
        $protein = $nutrients['たんぱく質'];
        $fat = $nutrients['脂質'];
        $carbohydrate = $nutrients['炭水化物'];

        //　上でuse宣言したうえで新しくingresdientsテーブルのデータを作成する
        $ingredient = new Ingredient();
        // 各カラムに値を入れる
        $ingredient->name = $food;
        $ingredient->protein = $protein;
        $ingredient->fat = $fat;
        $ingredient->carbohydrate = $carbohydrate;
        // $ingredientsをテーブルに保存
        $ingredient->save();

        // return dd("食材を保存しました",$protein,$fat,$carbohydrate);
        return redirect()->route('recipes.nutrient')->with('success', '食材を保存しました');
    }

}

