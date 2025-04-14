<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Nutrients</title>    
    </head>

    <body>

        <!-- フラッシュメッセージの表示 -->
        @if (session('success'))
            <div style="color:red; margin-bottom: 10px;">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div style="color: red; margin-bottom: 10px;">
                {{ session('error') }}
            </div>
        @endif

        <!-- コントローラークラスに入力情報保送るためにPOSTメソッドを送る -->
        <form action="/nutrientSearch" method="POST">
            @csrf
            <input type="text" name='nutrient' required placeholder="食材名を入力してください">
            <input type="submit" value="食材登録">
        </form>
    </body>
</html>