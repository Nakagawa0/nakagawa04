@if(session('success'))
    <div>{{ session('success') }}</div>
@endif

<form action="{{ route('suggested_recipes.store') }}" method="POST">
    @csrf
    <label>レシピ名: 
        <input type="text" name="name" value="{{ old('name') }}" required></label><br>
    <label>説明: 
        <textarea name="description">{{ old('description') }}</textarea></label><br>
    <label>手順: 
        <textarea name="instruction">{{ old('instrucition') }}</textarea></label><br>
    <label>たんぱく質 (g):
        <input type="number" name="protein" step="0.1" value="{{ old('protein') }}" required></label><br>
    <label>脂質 (g):<input type="number" name="fat" step="0.1" 
    value="{{ old('fat') }}" required></label><br>
    <label>炭水化物 (g):
        <input type="number" name="carbohydrate" step="0.1" value="{{ old('carbohydrate') }}" required></label><br>

    <button type="submit">登録</button>
</form>