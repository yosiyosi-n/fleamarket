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

        // 💡 1. まず先に「住所が未登録かどうか」をチェックして、不備があればエラーを記憶させます
        $addressError = null;
        if (!$profile || empty($profile->postal_code) || empty($profile->address)) {
            $addressError = '配送先住所が登録されていないため、購入できません。';
        }

        // 💡 2. Laravelのバリデーションを実行します（ここで住所のエラーも一緒に合流させます）
        $rules = [
            'payment_method' => ['required', 'in:コンビニ払い,カード払い'],
        ];

        $messages = [
            'payment_method.required' => '支払い方法を選択してください。',
            'payment_method.in' => '正しい支払い方法を選択してください。',
        ];

        // 💡 3. もし住所エラーが見つかっていたら、バリデーションに強制的にエラーを注入します
        if ($addressError) {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $messages);
            $validator->errors()->add('address_error', $addressError);
            
            // 支払い方法のエラーも同時に手動でチェックして、2つ揃った状態にします
            if (empty($request->payment_method)) {
                $validator->errors()->add('payment_method', '支払い方法を選択してください。');
            }

            return back()->withErrors($validator)->withInput();
        }

        // 💡 4. 住所が問題ない場合は、通常の支払い方法バリデーションを実行します
        $request->validate($rules, $messages);

        // 💡 5. すべての防衛線を突破したら、安全にデータベースに保存します
        \App\Models\Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item_id,
            'payment_method' => $request->payment_method,
            'postal_code' => $profile->postal_code,
            'address' => $profile->address,
            'building' => $profile->building,
        ]);

        return redirect('/');
    }

}
