<x-app-layout>
    <x-slot name="title">商品一覧 - フリマアプリ</x-slot>

    <!-- 📑 タブ切り替え（PG01 / PG02 用） -->
    <div style="background-color: #ffffff; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: center; gap: 40px;">
        <a href="/" style="color: #ff3333; text-decoration: none; font-weight: bold; padding: 15px 10px; border-bottom: 3px solid #ff3333;">おすすめ</a>
        <a href="/?tab=mylist" style="color: #666; text-decoration: none; padding: 15px 10px;">マイリスト</a>
    </div>

    <!-- 📦 メインコンテンツ（商品一覧エリア） -->
    <main style="max-width: 1024px; margin: 30px auto; padding: 0 20px;">
        <h2 style="font-size: 20px; margin-bottom: 20px; color: #333;">商品一覧</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px;">
            <!-- 商品カード1 -->
            <a href="/item/1" style="background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-decoration: none; color: #333;">
                <div style="width: 100%; aspect-ratio: 1; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center; color: #999;">NO IMAGE</div>
                <div style="padding: 10px;">
                    <div style="font-weight: bold; margin-bottom: 5px;">サンプル商品A</div>
                    <div style="color: #ff3333; font-weight: bold;">¥3,000</div>
                </div>
            </a>

            <!-- 商品カード2 -->
            <a href="/item/2" style="background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-decoration: none; color: #333;">
                <div style="width: 100%; aspect-ratio: 1; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center; color: #999;">NO IMAGE</div>
                <div style="padding: 10px;">
                    <div style="font-weight: bold; margin-bottom: 5px;">サンプル商品B</div>
                    <div style="color: #ff3333; font-weight: bold;">¥8,500</div>
                </div>
            </a>
        </div>
    </main>
</x-app-layout>
