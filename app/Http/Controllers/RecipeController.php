<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\RecipeIngredient;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Record;

class RecipeController extends Controller
{
    public function create()
    {
        $ingredients = Ingredient::all(); //食材を全取得
        return view('recipes.create', compact('ingredients')); //viewに渡す
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'instruction' => 'required|string',
            'ingredients' => 'required|array',
            'is_public' => 'nullable|boolean'
        ]);
        
        $ingredientDatas = $request->input('ingredients');
        $totalProtein = 0;
        $totalFat = 0;
        $totalCarbohydrate = 0;
       
        foreach ($ingredientDatas as $ingredientData) {
            $ingredient = Ingredient::find($ingredientData['id']);
            $weight = $ingredientData['weight'];
            
            //入力された量から計算
            $totalProtein += $ingredient->protein * ($weight / 100); 
            $totalFat += $ingredient->fat * ($weight / 100);
            $totalCarbohydrate += $ingredient->carbohydrate * ($weight / 100);
        }
        // 計算処理
        $calorie = round($totalCarbohydrate * 4 + $totalProtein * 4 + $totalFat * 9);

        // 登録処理
        $recipe = Recipe::create([
            'name' => $request->name,
            'description' => $request->description,
            'calorie' => $calorie,
            'instruction' => $request->instruction,
            'protein' => $totalProtein,
            'fat' => $totalFat,
            'carbohydrate' => $totalCarbohydrate,
            'is_public' => $request->has('is_public')
        ]);
        foreach($ingredientDatas as $ingredientData) {
            //recipe_ingredientsテーブルにレシピと食材の関係を保存
            $recipeIngredients = RecipeIngredient::create([
                'recipe_id' => (int)$recipe->id,
                'ingredient_id' => (int)$ingredientData['id'],
                'weight' => $ingredientData['weight']
            ]);
        }
        //userのrecordsテーブルに今日の食事を記録
        $user= Auth::id();
        $record = Record::create([
            'recipe_id' => (int)$recipe->id,
            'user_id' => $user,
            'meal_date' => Carbon::today()->toDateString()
        ]);


        return redirect()->route('recipes.create')->with('success', 'レシピを登録しました');
    }
    //食材の栄養素を検索する
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

