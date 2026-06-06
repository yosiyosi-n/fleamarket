<x-app-layout>
    <x-slot name="title">プロフィール設定 - フリマアプリ</x-slot>

    <div style="max-width: 500px; margin: 60px auto; padding: 40px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        <h2 style="text-align: center; margin-bottom: 35px; color: #333; font-size: 24px; font-weight: bold;">プロフィール設定</h2>

        <!-- 📸 画像をアップロードするため、enctype="multipart/form-data" を必ずつけます -->
        <form action="/mypage/profile" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- プロフィール画像設定エリア -->
            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 35px;">
                <!-- 過去設定された画像があれば表示、無ければグレーの円を表示 -->
                <div style="width: 80px; height: 80px; border-radius: 50%; background-color: #e0e0e0; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 1px solid #ccc;">
                    @if($profile && $profile->image_path)
                        <img src="{{ asset('storage/' . $profile->image_path) }}" alt="プロフィール画像" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span style="color: #999; font-size: 12px; font-weight: bold;">NO IMAGE</span>
                    @endif
                </div>
                <div>
                    <!-- 💡 JavaScriptなし・Laravel標準のファイル選択ボタン -->
                    <input type="file" name="image" accept="image/*" style="font-size: 14px;">
                </div>
            </div>

            <!-- ユーザー名（仕様書14番：初期値として過去設定されていること） -->
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #444;">ユーザー名</label>
                <!-- 💡 old('name', $user->name) と書くことで、過去のデータか、エラー時に打ち直した文字が自動で残ります -->
                <input type="text" name="name" value="{{ old('name', $profile->name ?? $user->name) }}" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px;">
            </div>

            <!-- 郵便番号（仕様書14番：初期値保持） -->
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #444;">郵便番号</label>
                <input type="text" name="postal_code" value="{{ old('postal_code', $profile->postal_code ?? '') }}" placeholder="123-4567" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px;">
            </div>

            <!-- 住所（仕様書14番：初期値保持） -->
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #444;">住所</label>
                <input type="text" name="address" value="{{ old('address', $profile->address ?? '') }}" placeholder="東京都渋谷区宇田川町1-1" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px;">
            </div>

            <!-- 建物名・部屋番号（あなたが提案してくれた親切な任意項目） -->
            <div style="margin-bottom: 40px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #444;">建物名（任意）</label>
                <input type="text" name="building" value="{{ old('building', $profile->building ?? '') }}" placeholder="コーチテックマンション 202" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px;">
            </div>

            <!-- 更新するボタン -->
            <button type="submit" style="width: 100%; padding: 16px; background-color: #ff3333; color: white; border: none; border-radius: 4px; font-size: 18px; font-weight: bold; cursor: pointer;">
                更新する
            </button>
        </form>
    </div>
</x-app-layout>
