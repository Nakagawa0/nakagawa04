@extends('layouts.app') 

@section('content')
<div class="container">
    <h2>食材登録</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('user_ingredients.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="ingredient_search" class="form-label">食材を検索して追加</label>
            <input type="text" id="ingredient_search" class="form-control" placeholder="食材名を入力..." autocomplete="off">
            <input type="hidden" name="ingredient_id" id="selected_ingredient_id" required>
            <div id="ingredient_suggestions" class="list-group" style="position: absolute; z-index: 1000; width: calc(100% - 30px); background-color: white; border: 1px solid #ddd; max-height: 200px; overflow-y: auto;">
            </div>
            <small class="form-text text-muted" id="selected_ingredient_display">選択された食材: なし</small>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">量(g)</label>
            <input type="number" step="0.1" name="quantity" class="form-control" value="{{ old('quantity') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">登録</button>
    </form>
    
    <div class="mt-3">
        <a href="{{ route('ingredients.create') }}" class="btn btn-success">その他の食材を追加</a> 
    </div>

    <div style="margin-top: 30px;">
        <a href="{{ url('/') }}" class="btn-primary">トップページに戻る</a>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('ingredient_search');
        const suggestionsDiv = document.getElementById('ingredient_suggestions');
        const selectedIngredientIdInput = document.getElementById('selected_ingredient_id');
        const selectedIngredientDisplay = document.getElementById('selected_ingredient_display');

        let searchTimeout;

        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const query = this.value;

            if (query.length < 2) {
                suggestionsDiv.innerHTML = '';
                selectedIngredientIdInput.value = '';
                selectedIngredientDisplay.textContent = '選択された食材: なし';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('ingredients.search') }}?query=${encodeURIComponent(query)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(ingredients => {
                        suggestionsDiv.innerHTML = '';
                        if (ingredients.length === 0) {
                            suggestionsDiv.innerHTML = '<button type="button" class="list-group-item list-group-item-action disabled">該当する食材が見つかりません</button>';
                            return;
                        }

                        ingredients.forEach(ingredient => {
                            const button = document.createElement('button');
                            button.type = 'button';
                            button.classList.add('list-group-item', 'list-group-item-action');
                            button.textContent = ingredient.name;
                            button.dataset.id = ingredient.id;
                            button.dataset.name = ingredient.name;

                            button.addEventListener('click', function () {
                                searchInput.value = this.dataset.name;
                                selectedIngredientIdInput.value = this.dataset.id;
                                selectedIngredientDisplay.textContent = `選択された食材: ${this.dataset.name}`;
                                suggestionsDiv.innerHTML = '';
                            });
                            suggestionsDiv.appendChild(button);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching ingredients:', error);
                        suggestionsDiv.innerHTML = '<button type="button" class="list-group-item list-group-item-action disabled text-danger">検索中にエラーが発生しました</button>';
                    });
            }, 300);
        });

        searchInput.addEventListener('blur', function() {
            setTimeout(() => {
                suggestionsDiv.innerHTML = '';
            }, 200);
        });
        suggestionsDiv.addEventListener('mousedown', function(event) {
            event.preventDefault();
        });
    });
</script>
@endpush
@endsection