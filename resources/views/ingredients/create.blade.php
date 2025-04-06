@extends('layouts.app') 

@section('content')
<div class="container">
    <h2>食材登録</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('ingredients.store') }}" method="POST">
        @csrf

        <!-- 入力-->
        <div class="mb-3">
            <label for="name" class="form-label">食材名</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
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

