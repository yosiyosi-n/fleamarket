<x-app-layout>
    <x-slot name="title">商品購入手続き - フリマアプリ</x-slot>

    <main style="max-width: 1024px; margin: 40px auto; padding: 0 20px; display: flex; gap: 40px; flex-wrap: wrap;">
        
        <!-- 📦 左側：商品情報・支払い方法・配送先設定エリア -->
        <div style="flex: 2; min-width: 300px;">
            
            <!-- ① 商品情報 -->
            <div style="display: flex; gap: 20px; border-bottom: 1px solid #e0e0e0; padding-bottom: 20px; margin-bottom: 30px;">
                <div style="width: 120px; aspect-ratio: 1; background-color: #e0e0e0; border-radius: 4px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    @if($item->image_path)
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span style="color: #999; font-size: 14px;">NO IMAGE</span>
                    @endif
                </div>
                <div>
                    <h1 style="font-size: 22px; font-weight: bold; margin: 0 0 10px 0; color: #333;">{{ $item->name }}</h1>
                    <div style="font-size: 20px; font-weight: bold; color: #ff3333;">¥{{ number_format($item->price) }}</div>
                </div>
            </div>

            <!-- ② 支払い方法（仕様書11番：小計画面へ反映させるために、選んだら自動で画面をリロードしてURLに値を残す仕組みを入れます） -->
            <div style="border-bottom: 1px solid #e0e0e0; padding-bottom: 25px; margin-bottom: 30px;">
                <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #333;">支払い方法</h2>
                <!-- 💡 教材の範囲内（HTMLのみ）でリアルタイム反映を実現するため、onchange="this.form.submit()" の代わりに、セレクトボックスが変更されたらURLを切り替える標準仕様にします -->
                <select onchange="location.href='?payment_method=' + this.value;" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; background-color: white; font-size: 16px;">
                    <option value="">選択してください</option>
                    <option value="コンビニ払い" {{ $paymentMethod == 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                    <option value="クレジットカード" {{ $paymentMethod == 'クレジットカード' ? 'selected' : '' }}>クレジットカード</option>
                    <option value="銀行振込" {{ $paymentMethod == '銀行振込' ? 'selected' : '' }}>銀行振込</option>
                </select>
            </div>

            <!-- ③ 配送先（仕様書12番：登録した住所を反映させ、右側にある「変更する」リンクから住所変更ページへ進めます） -->
            <div style="margin-bottom: 40px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h2 style="font-size: 18px; font-weight: bold; margin: 0; color: #333;">配送先</h2>
                    <!-- 💡 仕様書通りのパス /purchase/address/{item_id} へのリンク -->
                    <a href="/purchase/address/{{ $item->id }}" style="color: #0066cc; text-decoration: none; font-size: 15px; font-weight: bold;">変更する</a>
                </div>
                
                @if($profile && $profile->postal_code)
                    <div style="font-size: 16px; color: #444; line-height: 1.8; background-color: #fafafa; padding: 15px; border-radius: 6px; border: 1px solid #e8e8e8;">
                        <p style="margin: 0; font-weight: bold; color: #333; margin-bottom: 5px;">〒{{ $profile->postal_code }}</p>
                        <p style="margin: 0;">{{ $profile->address }} {{ $profile->building }}</p>
                    </div>
                @else
                    <!-- まだ住所が未登録の場合の警告（仕様書対策） -->
                    <div style="background-color: #fcf8e3; border: 1px solid #faebcc; color: #8a6d3b; padding: 15px; border-radius: 4px; font-size: 14px; line-height: 1.5;">
                        配送先住所が登録されていません。上の「変更する」から送付先住所を登録してください。
                    </div>
                @endif
            </div>
        </div>

        <!-- 🧾 右側：小計・購入確定セクション（仕様書11番確認エリア） -->
        <div style="flex: 1; min-width: 300px; max-width: 400px;">
            <div style="background-color: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #e0e0e0; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
                <h2 style="font-size: 20px; font-weight: bold; margin-top: 0; margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 15px; color: #333;">注文内容</h2>
                
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 16px;">
                    <tr style="height: 40px;">
                        <th style="text-align: left; font-weight: normal; color: #666;">商品代金</th>
                        <td style="text-align: right; font-weight: bold; color: #333;">¥{{ number_format($item->price) }}</td>
                    </tr>
                    <tr style="height: 40px; border-bottom: 1px solid #eee;">
                        <th style="text-align: left; font-weight: normal; color: #666;">支払い方法</th>
                        <!-- 💡 仕様書11番：選択された支払い方法がここにリアルタイムで文字として反映されます！ -->
                        <td style="text-align: right; font-weight: bold; color: #ff9900;">{{ $paymentMethod ?: '未選択' }}</td>
                    </tr>
                    <tr style="height: 60px;">
                        <th style="text-align: left; font-size: 18px; font-weight: bold; color: #333;">合計金額</th>
                        <td style="text-align: right; font-size: 22px; font-weight: bold; color: #ff3333;">¥{{ number_format($item->price) }}</td>
                    </tr>
                </table>

                <!-- 💡 本物の購入確定フォーム。隠しデータとして支払い方法などを一緒に送ります -->
                <form action="/purchase/{{ $item->id }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="{{ $paymentMethod }}">

                    <!-- 住所が未登録、または支払い方法が未選択のときはボタンを押せない（またはエラーにする）ように制御します -->
                    <button type="submit" style="width: 100%; padding: 15px; background-color: #ff3333; color: white; border: none; border-radius: 4px; font-size: 18px; font-weight: bold; cursor: pointer;" {{ empty($paymentMethod) || !$profile ? 'disabled style=background-color:#ccc;cursor:not-allowed;' : '' }}>
                        購入する
                    </button>
                </form>
            </div>
        </div>
    </main>
</x-app-layout>
