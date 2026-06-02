<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * 商品一覧画面（トップ画面）を表示する
     */
    public function index(Request $request)
    {
        // 💡 1. データベースから商品を取得する土台を作る（購入履歴データも一緒に読み込む）
        // ※のちほどリレーションを組むので、今の時点では「purchases」のままでOKです
        $query = Item::with('purchases');

        // 💡 2. 【仕様書の条件】「自分が出品した商品は表示されない」を実装
        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }

        // 最新順に並べ替えてデータを取得
        $items = $query->latest()->get();

        // 💡 修正：コロン2つ「item::index」になっていたのを、正しいドット「item.index」に直しました
        return view('item.index', compact('items'));
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

}
