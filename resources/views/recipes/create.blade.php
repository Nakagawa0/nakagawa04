@extends('layouts.app')

@section('content')
<div class="container">
    <h2>レシピ登録</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('recipes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">レシピ名</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">説明</label>
            <textarea name="description" class="form-control" required>{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="calorie" class="form-label">カロリー</label>
            <input type="number" name="calorie" class="form-control" value="{{ old('calorie') }}" required>
        </div>

        <div class="mb-3">
            <label for="instruction" class="form-label">手順</label>
            <textarea name="instruction" class="form-control" required>{{ old('instruction') }}</textarea>
        </div>

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
</div>
@endsection
