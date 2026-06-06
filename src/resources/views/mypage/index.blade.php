<x-app-layout>
    <x-slot name="title">マイページ - フリマアプリ</x-slot>

    <main style="max-width: 1024px; margin: 40px auto; padding: 0 20px;">
        
        <!-- 👤 🗂️ 上段：プロフィール情報エリア（仕様書項目） -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 40px; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); border: 1px solid #e0e0e0;">
            <div style="display: flex; align-items: center; gap: 25px;">
                <!-- プロフィール画像（仕様書項目） -->
                <div style="width: 100px; height: 100px; border-radius: 50%; background-color: #e0e0e0; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 1px solid #ccc;">
                    @if($profile && $profile->image_path)
                        <img src="{{ asset('storage/' . $profile->image_path) }}" alt="ユーザー画像" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span style="color: #999; font-size: 14px; font-weight: bold;">NO IMAGE</span>
                    @endif
                </div>
                <!-- ユーザー名（仕様書項目） -->
                <h1 style="font-size: 24px; font-weight: bold; color: #333; margin: 0;">
                    {{ $profile->name ?? $user->name }}
                </h1>
            </div>
            <!-- プロフィール編集画面へのリンクボタン -->
            <a href="/mypage/profile" style="border: 2px solid #ff3333; color: #ff3333; text-decoration: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; font-size: 15px; transition: all 0.2s;">
                プロフィールを編集
            </a>
        </div>

        <!-- 📑 中段：タブ切り替え（仕様書のURLルールに完全連動） -->
        <div style="border-bottom: 1px solid #e0e0e0; display: flex; gap: 40px; margin-bottom: 30px; font-size: 16px;">
            <!-- 出品した商品タブ -->
            <a href="/mypage?page=sell" style="text-decoration: none; font-weight: bold; padding: 15px 10px; {{ $page === 'sell' ? 'color: #ff3333; border-bottom: 3px solid #ff3333;' : 'color: #666;' }}">
                出品した商品
            </a>
            <!-- 購入した商品タブ -->
            <a href="/mypage?page=buy" style="text-decoration: none; font-weight: bold; padding: 15px 10px; {{ $page === 'buy' ? 'color: #ff3333; border-bottom: 3px solid #ff3333;' : 'color: #666;' }}">
                購入した商品
            </a>
        </div>

        <!-- 📦 下段：商品一覧エリア（切り替わった中身がループで並びます） -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px;">
            @forelse($items as $item)
                <a href="/item/{{ $item->id }}" style="background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05); text-decoration: none; color: #333; border: 1px solid #e0e0e0; display: block;">
                    <div style="width: 100%; aspect-ratio: 1; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center; color: #999;">
                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            NO IMAGE
                        @endif
                    </div>
                    <div style="padding: 12px;">
                        <div style="font-weight: bold; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item->name }}</div>
                        <div style="color: #ff3333; font-weight: bold;">¥{{ number_format($item->price) }}</div>
                    </div>
                </a>
            @empty
                <!-- 商品が1つもない場合の親切表示 -->
                <p style="color: #999; grid-column: 1 / -1; text-align: center; padding: 40px 0; font-size: 15px;">
                    {{ $page === 'sell' ? '出品した商品はまだありません。' : '購入した商品はまだありません。' }}
                </p>
            @endforelse
        </div>

    </main>
</x-app-layout>
