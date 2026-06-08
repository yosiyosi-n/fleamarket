<x-app-layout>
    <x-slot name="title">{{ $item->name }} - フリマアプリ</x-slot>

    <main style="max-width: 1024px; margin: 40px auto; padding: 0 20px; display: flex; gap: 50px; flex-wrap: wrap;">
        
        <!-- 📸 左側：商品画像エリア -->
        <div style="flex: 1; min-width: 300px; max-width: 500px;">
            <div style="width: 100%; aspect-ratio: 1; background-color: #e0e0e0; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: center;">
                @if($item->image_path)
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <span style="color: #999; font-size: 20px;">NO IMAGE</span>
                @endif
            </div>
        </div>

        <!-- 📝 右側：商品情報・購入エリア -->
        <div style="flex: 1; min-width: 300px;">
            <h1 style="font-size: 28px; font-weight: bold; margin: 0 0 10px 0; color: #333;">{{ $item->name }}</h1>
            <p style="font-size: 16px; color: #666; margin: 0 0 20px 0;">{{ $item->brand ?? 'ブランド情報なし' }}</p>
            
            <div style="font-size: 24px; font-weight: bold; color: #333; margin-bottom: 10px;">
                ¥{{ number_format($item->price) }} <span style="font-size: 14px; color: #666; font-weight: normal;">(税込)</span>
            </div>

            <!-- 📊 修正後：いいね(色変化＆データ送信対応)・コメント数カウンター表示エリア（仕様書項目） -->
            <div style="display: flex; gap: 20px;     padding-left: 30px; margin-bottom: 15px; align-items: center;">
                
                <!-- 💡 いいねボタンエリア -->
                <div style="text-align: center;">
                    @auth
                        <!-- 💡 ログイン時は、クリックすると裏側（web.phpのルート）へデータを送るフォームにします -->
                        <form action="/item/{{ $item->id }}/like" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer; display: block;">
                                <!-- 💡 あなたが考えてくれた完璧な判定文をここに合流させます -->
                                @if($item->likes->where('user_id', Auth::id())->isNotEmpty())
                                    <img src="{{ asset('img/heart-pink.png') }}" alt="いいね解除" style="width: 24px; height: 24px;">
                                @else
                                    <img src="{{ asset('img/heart-default.png') }}" alt="いいね登録" style="width: 24px; height: 24px;">
                                @endif
                            </button>
                        </form>
                    @endauth

                    @guest
                        <!-- 💡 ログイン前（ゲスト）はクリックできないただのハートを表示します -->
                        <img src="{{ asset('img/heart-default.png') }}" alt="いいね" style="width: 24px; height: 24px; opacity: 0.5;">
                    @endguest
                    
                    <!-- いいねの合計数カウンター（仕様書項目） -->
                    <div style="font-size: 14px; color: #555; margin-top: 4px;">{{ $likesCount }}</div>
                </div>

                <!-- コメントカウンターエリア -->
                <div style="text-align: center;">
                    <img src="{{ asset('img/comment-logo.png') }}" alt="コメント" style="width: 24px; height: 24px;">
                    <div style="font-size: 14px; color: #555; margin-top: 4px;">{{ $commentsCount }}</div>
                </div>
            </div>


            <!-- 🛒 修正後：売切ガード付き購入ボタン -->
            @if($item->purchases->isNotEmpty())
                <!-- 💡 すでに購入履歴がある（売り切れ）の場合 -->
                <div style="display: block; text-align: center; background-color: #bbb; color: white; padding: 15px; border-radius: 4px; font-size: 18px; font-weight: bold; margin-bottom: 40px; cursor: not-allowed;">
                    売り切れました
                </div>
            @else
                <!-- 💡 まだ誰にも買われていない（販売中）の場合のみボタンを表示 -->
                <a href="/purchase/{{ $item->id }}" style="display: block; text-align: center; background-color: #ff3333; color: white; text-decoration: none; padding: 15px; border-radius: 4px; font-size: 18px; font-weight: bold; margin-bottom: 40px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    購入手続きへ
                </a>
            @endif

            <!-- 📄 商品の説明（仕様書項目） -->
            <h2 style="font-size: 18px; padding-bottom: 8px; margin-bottom: 15px; color: #333;">商品説明</h2>
            <p style="font-size: 16px; line-height: 1.6; color: #444; margin-bottom: 40px; white-space: pre-wrap;">{{ $item->description }}</p>

            <!-- ℹ️ 商品の情報テーブル（仕様書項目・複数カテゴリ対応） -->
            <h2 style="font-size: 18px; margin-bottom: 15px; color: #333;">商品の情報</h2>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 40px; font-size: 16px;">
                <tr>
                    <th style="text-align: left; width: 30%; color: #555;">カテゴリー</th>
                    <td style="padding: 15px 10px; display: flex; flex-wrap: wrap; gap: 8px;">
                        @foreach($item->categories as $category)
                            <span style="background-color: #bbb; color: white; padding: 4px 12px; border-radius: 15px; font-size: 14px; font-weight: bold;">
                                {{ $category->name }}
                            </span>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th style="text-align: left; color: #555;">商品の状態</th>
                    <td style="padding: 15px 10px; color: #333;">{{ $item->condition }}</td>
                </tr>
            </table>

            <!-- 💬 過去のコメント一覧表示エリア（仕様書項目：ユーザー名と本文表示） -->
            <h2 style="font-size: 18px; padding-bottom: 8px; margin-bottom: 20px; color: #333;">コメント ({{ $commentsCount }})</h2>
            <div style="margin-bottom: 30px;">
                @forelse($item->comments as $comment)
                    <div style="margin-bottom: 20px;">
                        <!-- 💡 ユーザー情報エリア（画像と名前を横並びにするために flex を指定） -->
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                            
                            <!-- 📸 【追加！】コメントしたユーザーのプロフィール画像枠 -->
                            <div style="width: 32px; height: 32px; border-radius: 50%; background-color: #e0e0e0; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 1px solid #ccc; flex-shrink: 0;">
                                @if($comment->user->profile && $comment->user->profile->image_path)
                                    <img src="{{ asset('storage/' . $comment->user->profile->image_path) }}" alt="ユーザー画像" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <!-- 画像が未設定の場合はグレーの丸に小さなNO IMAGEかイニシャルなどを表示 -->
                                    <span style="color: #999; font-size: 8px; font-weight: bold; transform: scale(0.8);">NO IMG</span>
                                @endif
                            </div>

                            <!-- ユーザー名と投稿時間 -->
                            <div style="font-weight: bold; font-size: 14px; color: #333;">
                                {{ $comment->user->name }} 
                                <span style="font-weight: normal; color: #999; font-size: 12px; margin-left: 10px;">{{ $comment->created_at->format('Y/m/d H:i') }}</span>
                            </div>
                        </div>

                        <!-- コメント本文 -->
                        <p style="margin: 0; font-size: 15px; background-color: #bbb; color: #444; padding: 15px 15px; border-radius: 6px; line-height: 1.5; white-space: pre-wrap;">{{ $comment->comment }}</p>
                    </div>
                @empty
                    <p style="color: #999; font-size: 15px;">コメントはまだありません。</p>
                @endforelse
            </div>

            <!-- ⬇︎ 💡 コメント送信フォーム（仕様書9番対応） ⬇︎ -->
            <div style="border-radius: 6px;">
                <h3 style="font-size: 16px; font-weight: bold; margin: 0 0 15px 0; color: #333;">商品へのコメント</h3>

                <!-- 💡 【仕様書の条件】ログイン済みのユーザーだけにフォームを表示 -->
                @auth
                    <form action="/item/{{ $item->id }}/comment" method="POST">
                        @csrf
                        <textarea name="comment" rows="4" placeholder="コメントを入力してください（254文字以内）" style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('comment') ? '#ff3333' : '#ccc' }}; border-radius: 4px; box-sizing: border-box; font-size: 15px; resize: vertical; margin-bottom: 10px;">{{ old('comment') }}</textarea>
                        
                        <!-- ⬇︎ バリデーションエラーメッセージ ⬇︎ -->
                        @error('comment')
                            <div style="color: #ff3333; font-size: 14px; margin-bottom: 15px; font-weight: bold;">{{ $message }}</div>
                        @enderror

                        <button type="submit" style="background-color: #ff3333; color: white; border: none; padding: 12px 24px; border-radius: 4px; font-size: 15px; font-weight: bold; cursor: pointer; width: 100%;">
                            コメントを送信する
                        </button>
                    </form>
                @endauth

                <!-- 💡 【仕様書の条件】ログイン前のユーザーは送信できない（警告を表示） -->
                @guest
                    <div style="background-color: #fcf8e3; border: 1px solid #faebcc; color: #8a6d3b; padding: 15px; border-radius: 4px; font-size: 14px; text-align: center; line-height: 1.5;">
                        コメントを投稿するには、先に <a href="/login" style="color: #66512c; font-weight: bold; text-decoration: underline;">ログイン</a> または <a href="/register" style="color: #66512c; font-weight: bold; text-decoration: underline;">会員登録</a> を行う必要があります。
                    </div>
                @endguest
            </div>

        </div>
    </main>
</x-app-layout>
