<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'フリマアプリ' }}</title>
    
    @vite(['resources/css/app.css'])
</head>
<body style="margin: 0; font-family: sans-serif; background-color: #f5f5f5;">

    <!-- 🛍️ 全画面共通ヘッダー -->
    <header style="background-color: #ffffff; border-bottom: 1px solid #e0e0e0; padding: 15px 20px; display: flex; align-items: center; justify-content: space-between;">
        <!-- ロゴ（これは全画面共通で表示） -->
        <a href="/" style="font-size: 24px; font-weight: bold; color: #ff3333; text-decoration: none;">
            COACHTECH フリマ
        </a>

        <!-- ⬇︎ ログイン(login)と登録(register)以外のページだけで表示する ⬇︎ -->
        @if(!request()->is('login') && !request()->is('register'))
            <!-- 検索窓 -->
            <div style="flex-grow: 1; max-width: 500px; margin: 0 20px;">
                <input type="text" placeholder="なにをお探しですか？" style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>

            <!-- ナビゲーションメニュー -->
            <nav style="display: flex; gap: 15px;">
                <a href="/login" style="color: #333; text-decoration: none; padding: 8px 12px;">ログイン</a>
                <a href="/register" style="color: #333; text-decoration: none; padding: 8px 12px;">会員登録</a>
                <a href="/sell" style="background-color: #ff3333; color: white; text-decoration: none; padding: 8px 16px; border-radius: 4px; font-weight: bold;">出品</a>
            </nav>
        @endif
    </header>

    <!-- 📦 各画面の独自コンテンツがここに自動で挟み込まれます -->
    {{ $slot }}

</body>
</html>
