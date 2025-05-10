<!-- charts/getting_started.blade.php -->

@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Nutrition Pie Chart</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    </head>
    <body>
        <h1>{{ $formattedDate }}の栄養素バランス</h1>
        <canvas
            id="chart_getting_started"
            style="
                width: 50%; 
                margin: 20px auto;"
            width="50"
            height="50"
        ></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('ok');
            // ここに実行したい処理を書く
        console.log({{$totalProtein}});
        console.log({{$totalFat}});
        console.log({{$totalCarbohydrate}});
        });
        // import Chart from 'chart.js/auto';
        const ctx = document.getElementById('chart_getting_started');
        new Chart(ctx, {
            type: 'pie',
            data: {
            labels: ['totalProtein', 'totalFat', 'totalCarbohydrate'],
            datasets: [{
                label: 'total',
                data: [{{$totalProtein}}, {{$totalFat}}, {{$totalCarbohydrate}}],
                borderWidth: 1,
                backgroundColor: [
                '#2ecc71',
                '#e67e22',
                '#3498db'
                           ]
            }]
            },
            options: {
            scales: {
            y: {
                beginAtZero: true
            }
            }
            }
            }); 
            </script>
        @if (!empty($shortages))
            <div>
                <h2>栄養バランス</h2>
                <ul>
                    @foreach ($shortages as $msg)
                        <li>{{ $msg }}が不足しています</li>
                    @endforeach
        @endif
                </ul>
            </div>

            <div>
                <h2>提案レシピ</h2>
                <ul>
                    @for ($i=0; $i<count($shortages); $i++)
                        <p>{{ $shortages[$i] }}</p>
                        @foreach ($recommendedRecipes[$i] as $recipe)
                        <li>{{ $recipe->name }} (P: {{ $recipe->protein }}g / F: {{$recipe->fat}}g / C: {{ $recipe->carbohydrate }}g) </li>
                        @endforeach
                    @endfor
                </ul>
            </div>
        <div style="margin-top: 30px;">
            <a href="{{ url('/') }}" class="btn-primary">トップページに戻る</a>
        </div>
    </body>  
</html>
