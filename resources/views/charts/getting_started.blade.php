@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">{{ $formattedDate }} の栄養素バランス</h1>
    <canvas id="chart_getting_started" style="width:50%; margin:0 auto; display:block;" width="400" height="400"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('chart_getting_started').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Protein', 'Fat', 'Carbohydrate'],
                    datasets: [{
                        data: [{{ $totalProtein }}, {{ $totalFat }}, {{ $totalCarbohydrate }}],
                        borderWidth: 1,
                        backgroundColor: [
                            '#2ecc71',
                            '#e67e22',
                            '#3498db'
                        ]
                    }]
                },
                options: { responsive: true }
            });
        });
    </script>

    @if (!empty($shortages))
        <div class="mt-4">
            <h2>栄養素不足</h2>
            <ul>
                @foreach ($shortages as $msg)
                    <li>{{ $msg }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mt-4">
        <h2>栄養素ベースのおすすめレシピ</h2>
        <ul>
            @foreach ($nutrientBasedRecipes as $group)
                @foreach ($group as $recipe)
                    <li>{{ $recipe->name }} (P: {{ $recipe->protein }}g, F: {{ $recipe->fat }}g, C: {{ $recipe->carbohydrate }}g)</li>
                @endforeach
            @endforeach
        </ul>
    </div>

    <div class="mt-4">
        <h2>食材マッチ率ベースのおすすめレシピ</h2>
        <ul>
            @foreach ($ingredientBasedRecipes as $recipe)
                <li>{{ $recipe->name }} (P: {{ $recipe->protein }}g, F: {{ $recipe->fat }}g, C: {{ $recipe->carbohydrate }}g)</li>
            @endforeach
        </ul>
    </div>

    <div class="mt-5">
        <a href="{{ url('/') }}" class="btn btn-primary">トップページに戻る</a>
    </div>
</div>
@endsection
