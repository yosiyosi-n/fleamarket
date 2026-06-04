<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * 商品購入画面（PG06）を表示する
     */
    public function index($item_id, Request $request)
    {
        // 💡 1. データベースから購入対象の商品を取得
        $item = Item::findOrFail($item_id);

        // 💡 2. ログイン中のユーザーのプロフィール（住所情報）を取得
        $profile = Auth::user()->profile;

        // 💡 3. 仕様書11番：支払い方法の選択状態を保持（選択されていなければ未選択）
        // 画面のセレクトボックスが変更されたときに、URLから ?payment_method=xxx で受け取れるようにします
        $paymentMethod = $request->query('payment_method', '');

        // ⬇︎ 【★ここを追加！】もしすでに購入履歴があったら、強制的にトップへ送り返します ⬇︎
        if ($item->purchases->isNotEmpty()) {
            return redirect('/')->with('error', 'この商品はすでに売り切れです。');
        }

        // 画面（views/purchase/index.blade.php）にデータを渡して開きます
        return view('purchase.index', compact('item', 'profile', 'paymentMethod'));
    }

    /**
     * 送付先住所変更画面（PG07）を表示する
     */
    public function editAddress($item_id)
    {
        // 💡 1. どの商品から住所変更に来たかを保持するために、商品を取得
        $item = Item::findOrFail($item_id);

        // 💡 2. ログイン中のユーザーの既存のプロフィール（過去設定された住所）を取得
        $profile = Auth::user()->profile;

        // 画面（views/purchase/address.blade.php）にデータを渡して開きます
        return view('purchase.address', compact('item', 'profile'));
    }

    public function updateAddress(\App\Http\Requests\AddressRequest $request, $item_id)
    {
        $user = Auth::user();

        // 💡 ユーザーのプロフィールデータを上書き保存（無ければ新しく新規作成）します
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id], // 検索条件
            [
                'name' => $request->name,
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ]
        );

        // 保存がすべて完了したら、元の商品の「購入画面」へシュパッと戻します！
        return redirect('/purchase/' . $item_id);
    }

    /**
     * 商品の購入を確定し、履歴をデータベースに保存する（仕様書10番の裏側処理）
     */
    public function store(Request $request, $item_id)
    {
        $user = Auth::user();
        $profile = $user->profile;

        // 💡 仕様書12番：購入した商品に「その時の送付先住所」を紐づけて登録します
        \App\Models\Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item_id,
            'payment_method' => $request->payment_method, // 右側のフォームから隠しデータ（hidden）で届く支払い方法
            'postal_code' => $profile->postal_code,
            'address' => $profile->address,
            'building' => $profile->building,
        ]);

        // 💡 購入がすべて完了したら、トップページ（商品一覧画面）へ戻します
        return redirect('/');
    }
}
