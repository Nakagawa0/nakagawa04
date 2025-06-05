<!-- @extends('layouts.app') -->

<!-- @section('content') -->
<div style="text-align: right; margin-bottom: 20px;">
    <a href="{{ route('login') }}" class="btn-primary">ログイン</a>
    <a href="{{ route('register') }}" class="btn-primary" style="margin-left: 10px;">新規登録</a>
</div>
<div class="hero bg-cover bg-center min-h-screen flex flex-col items-center justify-center text-white" style="background-image: url('https://images.unsplash.com/photo-1600891964599-f61ba0e24092?auto=format&fit=crop&w=1500&q=80');">
    <div class="bg-black bg-opacity-60 p-10 rounded shadow-lg text-center">
        <h1 class="text-5xl font-extrabold mb-6">栄養レシピ</h1>
        <p class="text-xl mb-8">毎日の食生活記録、栄養バランスを考えたレシピ提案</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('recipes.create') }}" class="btn-main">レシピを記録する</a>
            <a href="{{ route('user_ingredients.create') }}" class="btn-sub">家の食材を登録する</a>
            <a href="/chart" class="btn-sub">栄養バランスをみる</a> {{-- 追加ボタン --}}
        </div>
    </div>
</div>
@endsection