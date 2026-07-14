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
    <header style="background-color: #000; border-bottom: 1px solid #e0e0e0; padding: 15px 20px; display: flex; align-items: center; justify-content: space-between;">
        <!-- ロゴ（これは全画面共通で表示） -->
        <a href="/" style="display: flex; align-items: center; text-decoration: none;">
            <!--
            💡 ポイント：asset('img/logo.png') と書くことで、
            他人のパソコンで起動した時も自動で正しい「URL/img/logo.png」に変換してくれます。
            -->
            <img src="{{ asset('img/coachtech-logo.png') }}" alt="COACHTECH" style="height: 40px; width: auto; object-fit: contain;">
        </a>


        <!-- ⬇︎ ログイン(login)と登録(register)以外のページだけで表示する ⬇︎ -->
        @if(!request()->is('login') && !request()->is('register') && !request()->is('email/verify'))
            <!-- ⬇︎ 💡 修正後：仕様書6番の検索条件を完全に保持して送信する本物のフォーム ⬇︎ -->
            <div style="flex-grow: 1; max-width: 500px; margin: 0 20px;">
                <form action="/" method="GET" style="margin: 0; display: flex;">
                    <!-- タブの状態（recommend または mylist）を隠しデータ（hidden）として一緒に送ることで、タブが保持されます -->
                    <input type="hidden" name="tab" value="{{ $tab ?? 'recommend' }}">
                    
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="なにをお探しですか？" style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 15px;">
                </form>
            </div>

            <!-- ナビゲーションメニュー -->
            <nav style="display: flex; gap: 15px; align-items: center;">
                <!-- 💡 ログインしている時（会員用メニュー） -->
                @auth
                    <!-- ログアウトボタン（Fortifyの仕様に合わせたPOST送信フォーム） -->
                    <form method="POST" action="/logout" style="margin: 0;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #ffffff; font-size: 16px; cursor: pointer; padding: 8px 12px;">
                            ログアウト
                        </button>
                    </form>
                    
                    <!-- 指示書のパス /mypage に修正 -->
                    <a href="/mypage" style="color: #ffffff; text-decoration: none; padding: 8px 12px;">マイページ</a>
                @endauth

                <!-- 💡 まだログインしていない時（ゲスト用メニュー） -->
                @guest
                    <a href="/login" style="color: #ffffff; text-decoration: none; padding: 8px 12px;">ログイン</a>
                    <a href="/register" style="color: #ffffff; text-decoration: none; padding: 8px 12px;">会員登録</a>
                @endguest

                <a href="/sell" style="background-color: #ffffff; color: #000; text-decoration: none; padding: 8px 16px; border-radius: 4px; font-weight: bold;">出品</a>
            </nav>
        @endif
    </header>

    <!-- 📦 各画面の独自コンテンツがここに自動で挟み込まれます -->
    {{ $slot }}

</body>
</html>
