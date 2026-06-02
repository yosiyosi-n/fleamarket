<x-app-layout>
    <x-slot name="title">商品の出品 - フリマアプリ</x-slot>

    <div style="max-width: 600px; margin: 50px auto; padding: 40px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        <h2 style="text-align: center; margin-bottom: 40px; color: #333; font-size: 24px; font-weight: bold;">商品の出品</h2>

        <form action="/sell" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- 商品画像 -->
            <div style="margin-bottom: 30px;">
                <label style="display: block; margin-bottom: 10px; font-weight: bold; color: #444;">商品画像</label>
                <input type="file" name="image" accept="image/*" style="width: 100%; padding: 15px; border: 2px dashed {{ $errors->has('image') ? '#ff3333' : '#ccc' }}; border-radius: 4px; box-sizing: border-box; background-color: #f9f9f9; cursor: pointer;">
                
                @error('image')
                    <div style="color: #ff3333; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <h3 style="font-size: 18px; border-bottom: 2px solid #333; padding-bottom: 8px; margin-bottom: 20px; color: #333;">商品の詳細</h3>

            <!-- カテゴリ -->
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 10px; font-weight: bold; color: #444;">カテゴリー（複数選択可）</label>
                <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                    @foreach($categories as $category)
                        <div style="margin: 0;">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" id="category-{{ $category->id }}" class="category-checkbox" {{ is_array(old('categories')) && in_array($category->id, old('categories')) ? 'checked' : '' }}>
                            <label for="category-{{ $category->id }}" class="category-label">
                                {{ $category->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <!-- ⬇︎ カテゴリーのエラーメッセージ表示 ⬇︎ -->
                @error('categories')
                    <div style="color: #ff3333; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- 商品の状態 -->
            <div style="margin-bottom: 30px;">
                <label style="display: block; margin-bottom: 10px; font-weight: bold; color: #444;">商品の状態</label>
                <select name="condition" style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('condition') ? '#ff3333' : '#ccc' }}; border-radius: 4px; box-sizing: border-box; background-color: white; font-size: 16px;">
                    <option value="">選択してください</option>
                    <option value="新品、未使用" {{ old('condition') == '新品、未使用' ? 'selected' : '' }}>新品、未使用</option>
                    <option value="未使用に近い" {{ old('condition') == '未使用に近い' ? 'selected' : '' }}>未使用に近い</option>
                    <option value="目立った傷や汚れなし" {{ old('condition') == '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                    <option value="やや傷や汚れあり" {{ old('condition') == 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                    <option value="傷や汚れあり" {{ old('condition') == '傷や汚れあり' ? 'selected' : '' }}>傷や汚れあり</option>
                    <option value="全体的に状態が悪い" {{ old('condition') == '全体的に状態が悪い' ? 'selected' : '' }}>全体的に状態が悪い</option>
                </select>
                <!-- ⬇︎ 商品の状態のエラーメッセージ表示 ⬇︎ -->
                @error('condition')
                    <div style="color: #ff3333; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <h3 style="font-size: 18px; border-bottom: 2px solid #333; padding-bottom: 8px; margin-bottom: 20px; color: #333;">商品名と説明</h3>

            <!-- 商品名 -->
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 10px; font-weight: bold; color: #444;">商品名</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="商品名（40文字まで）" style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('name') ? '#ff3333' : '#ccc' }}; border-radius: 4px; box-sizing: border-box; font-size: 16px;">
                <!-- ⬇︎ 商品名のエラーメッセージ表示 ⬇︎ -->
                @error('name')
                    <div style="color: #ff3333; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- ブランド名 -->
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 10px; font-weight: bold; color: #444;">ブランド名</label>
                <input type="text" name="brand" value="{{ old('brand') }}" placeholder="ブランド名（任意）" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px;">
            </div>

            <!-- 商品の説明 -->
            <div style="margin-bottom: 30px;">
                <label style="display: block; margin-bottom: 10px; font-weight: bold; color: #444;">商品の説明</label>
                <textarea name="description" rows="6" placeholder="商品の説明（色、素材、重さ、定価、注意点など）" style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('description') ? '#ff3333' : '#ccc' }}; border-radius: 4px; box-sizing: border-box; font-size: 16px; resize: vertical;">{{ old('description') }}</textarea>
                <!-- ⬇︎ 商品の説明のエラーメッセージ表示 ⬇︎ -->
                @error('description')
                    <div style="color: #ff3333; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <h3 style="font-size: 18px; border-bottom: 2px solid #333; padding-bottom: 8px; margin-bottom: 20px; color: #333;">販売価格</h3>

            <!-- 販売価格 -->
            <div style="margin-bottom: 40px;">
                <label style="display: block; margin-bottom: 10px; font-weight: bold; color: #444;">販売価格</label>
                <div style="display: flex; align-items: center;">
                    <span style="font-size: 20px; margin-right: 10px; font-weight: bold; color: #555;">¥</span>
                    <input type="number" name="price" value="{{ old('price') }}" placeholder="300 〜 9,999,999" style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('price') ? '#ff3333' : '#ccc' }}; border-radius: 4px; box-sizing: border-box; font-size: 16px;">
                </div>
                <!-- ⬇︎ 販売価格のエラーメッセージ表示 ⬇︎ -->
                @error('price')
                    <div style="color: #ff3333; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" style="width: 100%; padding: 16px; background-color: #ff3333; color: white; border: none; border-radius: 4px; font-size: 18px; font-weight: bold; cursor: pointer;">
                出品する
            </button>
        </form>
    </div>
</x-app-layout>
