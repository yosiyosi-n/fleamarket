<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
        /**
     * 商品一覧画面（トップ画面）
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');
        $search = $request->query('search', '');

        $query = Item::with('purchases');

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($tab === 'mylist') {
            if (Auth::check()) {
                $query->whereHas('likes', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            } else {
                $query->where('id', 0);
            }
        } else {
            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }
        }

        $items = $query->latest()->get();

        return view('item.index', compact('items', 'tab', 'search'));
    }


        /**
     * 商品詳細画面を表示する
     */
    public function show($item_id)
    {
        $item = \App\Models\Item::with(['categories', 'likes', 'comments.user'])->findOrFail($item_id);

        $likesCount = $item->likes->count();
        $commentsCount = $item->comments->count();

        return view('item.show', compact('item', 'likesCount', 'commentsCount'));
    }

    /**
     * 商品へのコメントを投稿・保存する
     */
    public function storeComment(\App\Http\Requests\CommentRequest $request, $item_id)
    {
        \App\Models\Comment::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'item_id' => $item_id,
            'comment' => $request->comment,
        ]);

        return redirect()->back();
    }

    /**
     * 商品出品画面を表示する
     */
    public function create()
    {
        $categories = \App\Models\Category::all();

        return view('item.create', compact('categories'));
    }

    /**
     * 出品された商品をデータベースに保存する
     */
    public function store(\App\Http\Requests\ExposeRequest $request)
    {
        $path = $request->file('image')->store('items', 'public');

        $item = \App\Models\Item::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'condition' => $request->condition,
            'price' => $request->price,
            'image_path' => $path,
        ]);

        $item->categories()->attach($request->categories);

        return redirect('/');
    }

        /**
     * いいねの登録・解除を切り替える
     */
    public function toggleLike($item_id)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        $like = $user->likes()->where('item_id', $item_id)->first();

        if ($like) {
            $like->delete();
        } else {
            $user->likes()->create([
                'item_id' => $item_id,
            ]);
        }

        return redirect()->back();
    }

    /**
     * 出品を取り消す
     */
    public function destroy(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        if ($item->user_id !== Auth::id()) {
            abort(403, '不正なアクセスです。');
        }

        if ($item->purchases->isNotEmpty()) {
            return redirect()->back()->with('error', 'すでに購入されているため、出品を取り消すことはできません。');
        }

        $item->delete();

        return redirect('/')->with('success', '出品を取り消しました。');
    }

}
