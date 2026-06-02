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

        // 画面（views/purchase/index.blade.php）にデータを渡して開きます
        return view('purchase.index', compact('item', 'profile', 'paymentMethod'));
    }
}
