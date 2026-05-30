<x-app-layout>
    <x-slot name="title">ログイン - フリマアプリ</x-slot>

    <div style="max-width: 400px; margin: 60px auto; padding: 30px;">
        <h2 style="text-align: center; margin-bottom: 30px; color: #333; font-size: 22px;">ログイン</h2>

        <!-- 🔐 ログイン処理を実行するFortifyのURL「/login」へPOSTで送信します -->
        <form action="/login" method="POST">
            <!-- 🔑 Laravelのセキュリティ対策（必須！） -->
            @csrf

            <!-- メールアドレス -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555;">メールアドレス</label>
                <!-- エラーがある時は枠線を赤くします -->
                <input type="email" name="email" value="{{ old('email') }}" style="width: 100%; padding: 10px; border: 1px solid {{ $errors->has('email') ? '#ff3333' : '#ccc' }}; border-radius: 4px; box-sizing: border-box;">
                
                @error('email')
                    <div style="color: #ff3333; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- パスワード -->
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555;">パスワード</label>
                <input type="password" name="password" style="width: 100%; padding: 10px; border: 1px solid {{ $errors->has('password') ? '#ff3333' : '#ccc' }}; border-radius: 4px; box-sizing: border-box;">
                
                @error('password')
                    <div style="color: #ff3333; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- ログインボタン -->
            <button type="submit" style="width: 100%; padding: 12px; background-color: #ff3333; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer;">
                ログインする
            </button>
        </form>

        <!-- 会員登録画面へのリンク -->
        <div style="text-align: center; margin-top: 20px;">
            <a href="/register" style="color: #0066cc; text-decoration: none; font-size: 14px;">会員登録はこちら</a>
        </div>
    </div>
</x-app-layout>
