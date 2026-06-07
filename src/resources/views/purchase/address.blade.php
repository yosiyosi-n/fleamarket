<x-app-layout>
    <x-slot name="title">送付先住所変更 - フリマアプリ</x-slot>

    <div style="max-width: 500px; margin: 60px auto; padding: 40px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        <h2 style="text-align: center; margin-bottom: 35px; color: #333; font-size: 22px; font-weight: bold;">住所の変更</h2>

        <form action="/purchase/address/{{ $item->id }}" method="POST">
            @csrf

            <!-- 送付先 氏名 -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555;">お名前</label>
                <input type="text" name="name" value="{{ old('name', $profile->name ?? '') }}" style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('name') ? '#ff3333' : '#ccc' }}; border-radius: 4px; box-sizing: border-box; font-size: 16px;">
                <!-- 💡 お名前のエラーモニター -->
                @error('name')
                    <div style="color: #ff3333; font-size: 14px; margin-top: 5px; font-weight: bold;">{{ $message }}</div>
                @enderror
            </div>

            <!-- 郵便番号 -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555;">郵便番号（ハイフンあり）</label>
                <input type="text" name="postal_code" value="{{ old('postal_code', $profile->postal_code ?? '') }}" placeholder="123-4567" style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('postal_code') ? '#ff3333' : '#ccc' }}; border-radius: 4px; box-sizing: border-box; font-size: 16px;">
                <!-- 💡 郵便番号のエラーモニター -->
                @error('postal_code')
                    <div style="color: #ff3333; font-size: 14px; margin-top: 5px; font-weight: bold;">{{ $message }}</div>
                @enderror
            </div>

            <!-- 住所 -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555;">住所</label>
                <input type="text" name="address" value="{{ old('address', $profile->address ?? '') }}" placeholder="東京都渋谷区宇田川町1-1" style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('address') ? '#ff3333' : '#ccc' }}; border-radius: 4px; box-sizing: border-box; font-size: 16px;">
                <!-- 💡 住所のエラーモニター -->
                @error('address')
                    <div style="color: #ff3333; font-size: 14px; margin-top: 5px; font-weight: bold;">{{ $message }}</div>
                @enderror
            </div>

            <!-- 建物名（任意） -->
            <div style="margin-bottom: 35px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555;">建物名・部屋番号（任意）</label>
                <input type="text" name="building" value="{{ old('building', $profile->building ?? '') }}" placeholder="コーチテックビル 101" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px;">
            </div>

            <!-- 更新するボタン -->
            <button type="submit" style="width: 100%; padding: 15px; background-color: #ff3333; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer;">
                更新する
            </button>
        </form>
    </div>
</x-app-layout>
