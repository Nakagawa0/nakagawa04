@extends('layouts.app') 

@section('content')
<div class="container">
    <h2>食材登録</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('user_ingredients.store') }}" method="POST">
        @csrf

        <!-- 食材選択-->
        <div class="mb-3">
            <label for="name" class="form-label">食材</label>
            <select name="ingredient_id" class="form-control" required>
                <option value="">食材を選択</option>
                @foreach($ingredients as $ingredient)
                    <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">量(g)</label>
            <input type="number" step="0.1" name="quantity" class="form-control" value="{{ old('fat') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">登録</button>
    </form>
    
    <div style="margin-top: 30px;">
    <a href="{{ url('/') }}" class="btn-primary">トップページに戻る</a>
</div>
</div>
@endsection

