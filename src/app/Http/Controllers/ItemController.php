<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
        /**
     * 商品一覧画面（トップ画面）を表示する（検索 ＆ マイリスト完全対応版）
     */
    public function index(Request $request)
    {
        // 💡 1. URLから「現在のタブ（おすすめ or マイリスト）」と「検索文字」を取得
        $tab = $request->query('tab', 'recommend'); // デフォルトはおすすめ
        $search = $request->query('search', '');     // デフォルトは空欄

        // 💡 2. データベースから商品を取得する土台を作る
        $query = Item::with('purchases');

        // 💡 3. 【仕様書6番：商品検索機能】「商品名」で部分一致検索ができる
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // 💡 4. タブに応じた商品の出し分け（仕様書4番 ＆ 5番）
        if ($tab === 'mylist') {
            // ⭐ 【仕様書5番：マイリスト】いいねした商品だけを表示する
            if (Auth::check()) {
                // 自分がいいね（likes）した商品だけに厳しく絞り込みます
                $query->whereHas('likes', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            } else {
                // 【仕様書5番の条件】未認証の場合は何も表示されない
                // 1件もヒットしない絶対にありえない条件（idが0）を入れて空っぽにします
                $query->where('id', 0);
            }
        } else {
            // 🛍️ 【仕様書4番：おすすめ】自分が出品した商品は表示されない
            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }
        }

        // 最新順に並べ替えてデータを取得
        $items = $query->latest()->get();

        // 画面（views/item/index.blade.php）に全ての状態を渡して表示します
        return view('item.index', compact('items', 'tab', 'search'));
    }


        /**
     * 商品詳細画面を表示する
     */
    public function show($item_id)
    {
        // 💡 仕様書の指定項目（カテゴリ、いいね、コメント、コメントしたユーザー）をすべて1回の通信でまとめて取得
        $item = \App\Models\Item::with(['categories', 'likes', 'comments.user'])->findOrFail($item_id);

        // 💡 いいね数とコメント数をカウント
        $likesCount = $item->likes->count();
        $commentsCount = $item->comments->count();

        // 画面（views/item/show.blade.php）にすべてのデータを渡して表示します
        return view('item.show', compact('item', 'likesCount', 'commentsCount'));
    }

    /**
     * 商品へのコメントを投稿・保存する（仕様書9番の裏側処理）
     */
    public function storeComment(\App\Http\Requests\CommentRequest $request, $item_id)
    {
        // 💡 データベースの comments テーブルに新しいレコードを保存します
        \App\Models\Comment::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(), // ログイン中のユーザーID
            'item_id' => $item_id,                             // 対象の商品ID
            'comment' => $request->comment,                    // コメント本文
        ]);

        // 保存が終わったら、元の詳細画面へ戻します
        return redirect()->back();
    }

    /**
     * 商品出品画面を表示する（★消さずに残しておきます！）
     */
    public function create()
    {
        // 💡 データベースからすべてのカテゴリ（13個）を取得します
        $categories = \App\Models\Category::all();

        // 画面（views/item/create.blade.php）にカテゴリデータを渡して開きます
        return view('item.create', compact('categories'));
    }

    /**
     * 出品された商品をデータベースに保存する（仕様書15番の裏側処理）
     */
    public function store(\App\Http\Requests\ExposeRequest $request)
    {
        // 1. 画像ファイルを「public/items」という鍵付きフォルダに自動保存し、そのパス（住所）を取得
        $path = $request->file('image')->store('items', 'public');

        // 2. items テーブルに商品の基本情報を保存
        $item = \App\Models\Item::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(), // 今ログインしているユーザーのID
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'condition' => $request->condition,
            'price' => $request->price,
            'image_path' => $path,
        ]);

        // 3. 【仕様書7本の複数選択対策】中間テーブル（category_item）に、選ばれた複数のカテゴリIDを一斉に紐付け保存
        $item->categories()->attach($request->categories);

        // 保存がすべて完了したら、トップページ（商品一覧）へ戻します
        return redirect('/');
    }

        /**
     * いいねの登録・解除を切り替える（仕様書8番の裏側処理・修正版）
     */
    public function toggleLike($item_id)
    {
        // 💡 今ログインしているユーザーの「いいねデータ」を直接操作します
        $user = \Illuminate\Support\Facades\Auth::user();

        // もし何かの手違いでログインが外れていた場合は、安全のためにログイン画面へ戻します
        if (!$user) {
            return redirect('/login');
        }

        // 💡 ユーザーの過去のいいねの中から、この商品IDのものを検索
        $like = $user->likes()->where('item_id', $item_id)->first();

        if ($like) {
            // ① すでにいいねがあれば「解除（削除）」
            $like->delete();
        } else {
            // ② なければ「新しく登録」
            $user->likes()->create([
                'item_id' => $item_id,
            ]);
        }

        // 画面をそのままで再読み込み（リフレッシュ）させます
        return redirect()->back();
    }

        /**
     * 出品を取り消す（商品を削除する）
     */
    public function destroy($item_id)
    {
        // 💡 1. データベースから削除対象の商品を取得
        $item = Item::findOrFail($item_id);

        // 💡 2. 安全対策：万が一他人がURLを直接叩いて削除しようとした場合をガード
        if ($item->user_id !== Auth::id()) {
            abort(403, '不正なアクセスです。');
        }

        // 💡 3. 商品をデータベースから完全に消去
        $item->delete();

        // 💡 4. 完了したら、トップページ（商品一覧）へシュパッと戻します！
        return redirect('/')->with('success', '出品を取り消しました。');
    }

}
