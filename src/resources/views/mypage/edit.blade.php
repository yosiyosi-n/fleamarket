<x-app-layout>
    <x-slot name="title">プロフィール設定 - フリマアプリ</x-slot>

    <div style="max-width: 500px; margin: 60px auto; padding: 40px;">
        <h2 style="text-align: center; margin-bottom: 35px; color: #333; font-size: 24px; font-weight: bold;">プロフィール設定</h2>

        <form action="/mypage/profile" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- プロフィール画像設定エリア -->
            <div style="display: flex; align-items: center; gap: 25px; margin-bottom: 35px; border: 1px dashed {{ $errors->has('image') ? '#ff3333' : 'transparent' }}; flex-wrap: wrap;">
                
                <!-- 現在のプロフィール画像 -->
                <div style="width: 80px; height: 80px; border-radius: 50%; background-color: #e0e0e0; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 1px solid #ccc; flex-shrink: 0;">
                    @if($profile && $profile->image_path)
                        <img src="{{ asset('storage/' . $profile->image_path) }}" alt="プロフィール画像" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span style="color: #999; font-size: 12px; font-weight: bold;">NO IMAGE</span>
                    @endif
                </div>

                <!-- ボタン配置エリア -->
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: inline-block; padding: 10px 20px; border: 1px solid #ff3333; border-radius: 10px; background-color: white; font-size: 14px; font-weight: bold; color: #ff3333; cursor: pointer; transition: background-color 0.2s; text-align: center; user-select: none;">
                        画像を選択する
                        
                        <input type="file" name="image" accept="image/*" hidden>
                    </label>

                    @if($errors->has('name') || $errors->has('postal_code') || $errors->has('address') || $errors->has('image'))
                        <div style="font-size: 11px; font-weight: bold; color: #888; margin-top: 8px; line-height: 1.4;">
                            ⚠️ 画面が戻った際は、セキュリティ上画像の再選択が必要です
                        </div>
                    @endif

                    <!-- 画像エラーモニター -->
                    @error('image')
                        <div style="color: #ff3333; font-size: 14px; margin-top: 5px; font-weight: bold;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- ユーザー名 -->
            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: bold; color: #444;">ユーザー名</label>
                <input type="text" name="name" value="{{ old('name', $profile->name ?? $user->name) }}" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px;">
                @error('name')
                    <div style="color: #ff3333; font-size: 14px; margin-top: 5px; font-weight: bold;">{{ $message }}</div>
                @enderror
            </div>

            <!-- 郵便番号 -->
            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: bold; color: #444;">郵便番号</label>
                <input type="text" name="postal_code" value="{{ old('postal_code', $profile->postal_code ?? '') }}" placeholder="123-4567" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px;">
                @error('postal_code')
                    <div style="color: #ff3333; font-size: 14px; margin-top: 5px; font-weight: bold;">{{ $message }}</div>
                @enderror
            </div>

            <!-- 住所 -->
            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: bold; color: #444;">住所</label>
                <input type="text" name="address" value="{{ old('address', $profile->address ?? '') }}" placeholder="東京都渋谷区宇田川町1-1" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px;">
                @error('address')
                    <div style="color: #ff3333; font-size: 14px; margin-top: 5px; font-weight: bold;">{{ $message }}</div>
                @enderror
            </div>

            <!-- 建物名・部屋番号 -->
            <div style="margin-bottom: 40px;">
                <label style="display: block; font-weight: bold; color: #444;">建物名</label>
                <input type="text" name="building" value="{{ old('building', $profile->building ?? '') }}" placeholder="コーチテックマンション 202" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px;">
            </div>

            <!-- 更新ボタン -->
            <button type="submit" style="width: 100%; padding: 16px; background-color: #ff3333; color: white; border: none; border-radius: 4px; font-size: 18px; font-weight: bold; cursor: pointer;">
                更新する
            </button>
        </form>
    </div>
</x-app-layout>
