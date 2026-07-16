<x-app-layout>
    <x-slot name="title">{{ $item->name }} - フリマアプリ</x-slot>

    <main style="max-width: 1024px; margin: 40px auto; padding: 0 20px; display: flex; gap: 50px; flex-wrap: wrap;">
        
        <!-- 左側：商品画像エリア -->
        <div style="flex: 1; min-width: 300px; max-width: 500px;">
            <div style="width: 100%; aspect-ratio: 1; background-color: #e0e0e0; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: center;">
                @if($item->image_path)
                    @if(str_starts_with($item->image_path, 'http'))
                        <img src="{{ $item->image_path }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @endif
                @else
                    <span style="color: #999; font-size: 20px;">NO IMAGE</span>
                @endif
            </div>
        </div>

        <!-- 右側：商品情報・購入エリア -->
        <div style="flex: 1; min-width: 300px;">
            <h1 style="font-size: 28px; font-weight: bold; margin: 0 0 10px 0; color: #333;">{{ $item->name }}</h1>
            <p style="font-size: 16px; color: #666; margin: 0 0 20px 0;">{{ $item->brand ?? 'ブランド情報なし' }}</p>
            
            <div style="font-size: 24px; font-weight: bold; color: #333; margin-bottom: 25px;">
                ¥{{ number_format($item->price) }} <span style="font-size: 14px; color: #666; font-weight: normal;">(税込)</span>
            </div>

            <!--【条件分岐】もし自分が出品した商品だったら（出品者モード） -->
            @if(Auth::check() && $item->user_id === Auth::id())

                <!-- 出品取り消しボタン -->
                <form action="/item/{{ $item->id }}/delete" method="POST" onsubmit="return confirm('本当にこの商品の出品を取り消しますか？');" style="margin-bottom: 40px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="width: 100%; text-align: center; background-color: #ff3333; color: white; border: none; padding: 15px; border-radius: 4px; font-size: 18px; font-weight: bold; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        出品を取り消す
                    </button>
                </form>

            @else

                <!-- いいね・コメント数カウンター表示エリア -->
                <div style="display: flex; gap: 20px; margin-bottom: 30px; align-items: center;">
                    <!-- いいねボタンエリア -->
                    <div style="text-align: center;">
                        @auth
                            <form action="/item/{{ $item->id }}/like" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer; display: block;">
                                    @if($item->likes->where('user_id', Auth::id())->isNotEmpty())
                                        <img src="{{ asset('img/heart-pink.png') }}" alt="いいね解除" style="width: 24px; height: 24px;">
                                    @else
                                        <img src="{{ asset('img/heart-default.png') }}" alt="いいね登録" style="width: 24px; height: 24px;">
                                    @endif
                                </button>
                            </form>
                        @endauth
                        @guest
                            <img src="{{ asset('img/heart-default.png') }}" alt="いいね" style="width: 24px; height: 24px; opacity: 0.5;">
                        @endguest
                        <div style="font-size: 14px; color: #555; margin-top: 4px;">{{ $likesCount }}</div>
                    </div>

                    <!-- コメントカウンターエリア -->
                    <div style="text-align: center;">
                        <img src="{{ asset('img/comment-logo.png') }}" alt="コメント" style="width: 24px; height: 24px;">
                        <div style="font-size: 14px; color: #555; margin-top: 4px;">{{ $commentsCount }}</div>
                    </div>
                </div>

                <!-- 売切ガード付き購入ボタン -->
                @if($item->purchases->isNotEmpty())
                    <div style="display: block; text-align: center; background-color: #bbb; color: white; padding: 15px; border-radius: 4px; font-size: 18px; font-weight: bold; margin-bottom: 40px; cursor: not-allowed;">
                        売り切れました
                    </div>
                @else
                    <a href="/purchase/{{ $item->id }}" style="display: block; text-align: center; background-color: #ff3333; color: white; text-decoration: none; padding: 15px; border-radius: 4px; font-size: 18px; font-weight: bold; margin-bottom: 40px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        購入手続きへ
                    </a>
                @endif

            @endif

            <!-- 商品の説明（共通表示） -->
            <h2 style="font-size: 18px; padding-bottom: 8px; margin-bottom: 15px; color: #333;">商品説明</h2>
            <p style="font-size: 16px; line-height: 1.6; color: #444; margin-bottom: 40px; white-space: pre-wrap;">{{ $item->description }}</p>

            <!-- 商品の情報テーブル（共通表示） -->
            <h2 style="font-size: 18px; margin-bottom: 15px; color: #333;">商品情報</h2>
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

            <!-- 過去のコメント一覧表示エリア -->
            <h2 style="font-size: 18px; padding-bottom: 8px; margin-bottom: 20px; color: #333;">コメント ({{ $commentsCount }})</h2>
            <div style="margin-bottom: 30px;">
                @forelse($item->comments as $comment)
                    <div style="margin-bottom: 20px;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background-color: #e0e0e0; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 1px solid #ccc; flex-shrink: 0;">
                                @if($comment->user->profile && $comment->user->profile->image_path)
                                    <img src="{{ asset('storage/' . $comment->user->profile->image_path) }}" alt="ユーザー画像" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span style="color: #999; font-size: 8px; font-weight: bold; transform: scale(0.8);">NO IMG</span>
                                @endif
                            </div>
                            <div style="font-weight: bold; font-size: 14px; color: #333;">
                                {{ $comment->user->name }} 
                                <span style="font-weight: normal; color: #999; font-size: 12px; margin-left: 10px;">{{ $comment->created_at->format('Y/m/d H:i') }}</span>
                            </div>
                        </div>
                        <p style="margin: 0; font-size: 15px; background-color: #bbb; color: #444; padding: 15px 15px; border-radius: 6px; line-height: 1.5; white-space: pre-wrap;">{{ $comment->comment }}</p>
                    </div>
                @empty
                    <p style="color: #999; font-size: 15px;">コメントはまだありません。</p>
                @endforelse
            </div>

            <!-- コメント送信フォーム -->
            <div style="border-radius: 6px;">
                <h3 style="font-size: 16px; font-weight: bold; margin: 0 0 15px 0; color: #333;">商品へのコメント</h3>

                @auth
                    <form action="/item/{{ $item->id }}/comment" method="POST">
                        @csrf
                        <textarea name="comment" rows="4" placeholder="コメントを入力してください（254文字以内）" style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('comment') ? '#ff3333' : '#ccc' }}; border-radius: 4px; box-sizing: border-box; font-size: 15px; resize: vertical; margin-bottom: 10px;">{{ old('comment') }}</textarea>
                        @error('comment')
                            <div style="color: #ff3333; font-size: 14px; margin-bottom: 15px; font-weight: bold;">{{ $message }}</div>
                        @enderror
                        <button type="submit" style="background-color: #ff3333; color: white; border: none; padding: 12px 24px; border-radius: 4px; font-size: 15px; font-weight: bold; cursor: pointer; width: 100%;">
                            コメントを送信する
                        </button>
                    </form>
                @endauth

                @guest
                    <div style="background-color: #fcf8e3; border: 1px solid #faebcc; color: #8a6d3b; padding: 15px; border-radius: 4px; font-size: 14px; text-align: center; line-height: 1.5;">
                        コメントを投稿するには、先に <a href="/login" style="color: #66512c; font-weight: bold; text-decoration: underline;">ログイン</a> または <a href="/register" style="color: #66512c; font-weight: bold; text-decoration: underline;">会員登録</a> を行う必要があります。
                    </div>
                @endguest
            </div>

        </div>
    </main>
</x-app-layout>
