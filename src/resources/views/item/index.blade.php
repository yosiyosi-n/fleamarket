<x-app-layout>
    <x-slot name="title">商品一覧 - フリマアプリ</x-slot>

    <!-- タブ切り替え -->
    <div style="background-color: #ffffff; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: center; gap: 40px;">
        <a href="/?tab=recommend&search={{ urlencode($search) }}" style="text-decoration: none; font-weight: bold; padding: 15px 10px; {{ $tab !== 'mylist' ? 'color: #ff3333; border-bottom: 3px solid #ff3333;' : 'color: #666;' }}">おすすめ</a>
        <a href="/?tab=mylist&search={{ urlencode($search) }}" style="text-decoration: none; font-weight: bold; padding: 15px 10px; {{ $tab === 'mylist' ? 'color: #ff3333; border-bottom: 3px solid #ff3333;' : 'color: #666;' }}">マイリスト</a>
    </div>

    <!-- メインコンテンツ（商品一覧エリア） -->
    <main style="max-width: 1024px; margin: 30px auto; padding: 0 20px;">
        
        @if(!empty($search))
            <p style="color: #666; font-size: 15px; margin-bottom: 20px;">「<strong style="color: #333;">{{ $search }}</strong>」の検索結果（{{ $items->count() }}件）</p>
        @endif

        <h2 style="font-size: 20px; margin-bottom: 20px; color: #333;">
            {{ $tab === 'mylist' ? 'マイリスト一覧' : 'おすすめ商品' }}
        </h2>
        
        <!-- 商品が並ぶグリッド -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
            
            @forelse($items as $item)
                <a href="/item/{{ $item->id }}" style="background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-decoration: none; color: #333; position: relative; display: block; border: 1px solid #e0e0e0;">
                    
                    <!-- 購入済み商品は「Sold」と表示される -->
                    @if($item->purchases->isNotEmpty())
                        <div style="position: absolute; top: 0; left: 0; background-color: rgba(255,0,0,0.8); color: white; padding: 5px 10px; font-weight: bold; font-size: 14px; z-index: 10; border-bottom-right-radius: 8px;">
                            Sold
                        </div>
                    @endif

                    <!-- 商品画像エリア -->
                    <div style="width: 100%; aspect-ratio: 1; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center; color: #999;">
                        @if($item->image_path)
                            @if(str_starts_with($item->image_path, 'http'))
                                <img src="{{ $item->image_path }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @endif
                        @else
                            NO IMAGE
                        @endif
                    </div>

                    <!-- 商品情報エリア -->
                    <div style="padding: 12px;">
                        <div style="font-weight: bold; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item->name }}</div>
                        <div style="color: #ff3333; font-weight: bold;">¥{{ number_format($item->price) }}</div>
                    </div>
                </a>
            @empty
                <!-- 商品が1つも登録されていない時の表示 -->
                <p style="color: #999; grid-column: 1 / -1; text-align: center; padding: 40px 0; font-size: 15px;">
                    {{ $tab === 'mylist' ? 'いいねした商品はまだありません。' : '該当する商品がありません。' }}
                </p>
            @endforelse

        </div>
    </main>
</x-app-layout>
