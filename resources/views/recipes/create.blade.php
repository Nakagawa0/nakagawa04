@extends('layouts.app')

@section('content')
<div class="container">
    <h2>食事レシピ記録</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('recipes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">レシピ名</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">説明</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="instruction" class="form-label">手順</label>
            <textarea name="instruction" class="form-control" required></textarea>
        </div>
<!--
        <div class="mb-3">
            <label for="protein" class="form-label">たんぱく質 (g)</label>
            <input type="number" step="0.1" name="protein" class="form-control" value="{{ old('protein') }}" required>
        </div>

        <div class="mb-3">
            <label for="fat" class="form-label">脂質 (g)</label>
            <input type="number" step="0.1" name="fat" class="form-control" value="{{ old('fat') }}" required>
        </div>

        <div class="mb-3">
            <label for="carbohydrate" class="form-label">炭水化物 (g)</label>
            <input type="number" step="0.1" name="carbohydrate" class="form-control" value="{{ old('carbohydrate') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">登録</button>
    </form>
-->    
    <!-- 使用する食材、その量を入力 -->
        <h3>使用する食材を追加</h3>
        <div id="ingredients-container">
            <div class="mb-3 ingredient-row"> 
                <label for="ingredients[0][id]" class="form-label">食材</label>
                <select name="ingredients[0][id]" class="form-select" required>
                    @foreach ($ingredients as $ingredient)
                        <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                    @endforeach
                </select>

                <label for="ingredients[0][weight]" class="form-label">使用量</label>
                <input type="number" name="ingredients[0][weight]" class="form-control" step="0.1" required>
            </div>
        </div>
    
        <!-- 食材入力欄を増やす -->
        <button type="button" onclick="addIngredientRow()" class="btn btn-secondary mb-3">食材を追加</button>
        <button type="submit" class="btn btn-primary">登録</button>
    </form>
    
    <script>
        let ingredientIndex = 1;
        function addIngredientRow() {
            const container = document.getElementById('ingredients-container');
            //新しい入力欄を作成
            const newRow = document.createElement('div'); 
            newRow.classList.add('mb-3', 'ingredient-row');
            newRow.innerHTML = `
                <label class="form-label">食材</label>
                <select name="ingredients[${ingredientIndex}][id]" class="form-select" required>
                    @foreach ($ingredients as $ingredient)
                        <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                    @endforeach
                </select>

                <label for ="form-label">使用量(g)</label>
                <input type="number" name="ingredients[${ingredientIndex}][weight]" class="form-control" step="0.1" required>
            `;
            container.appendChild(newRow);
            ingredientIndex++;
        }
    </script> 
    <div style="margin-top: 30px;">
    <a href="{{ url('/') }}" class="btn-primary">トップページに戻る</a>
    </div>   
</div>
@endsection
