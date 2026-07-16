<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * 商品購入画面を表示する
     */
    public function index($item_id, Request $request)
    {
        $item = Item::findOrFail($item_id);

        $profile = Auth::user()->profile;

        $paymentMethod = $request->query('payment_method', '');

        if ($item->purchases->isNotEmpty()) {
            return redirect('/')->with('error', 'この商品はすでに売り切れです。');
        }

        return view('purchase.index', compact('item', 'profile', 'paymentMethod'));
    }

    /**
     * 送付先住所変更画面を表示する
     */
    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);

        $profile = Auth::user()->profile;

        return view('purchase.address', compact('item', 'profile'));
    }

    public function updateAddress(\App\Http\Requests\AddressRequest $request, $item_id)
    {
        $user = Auth::user();

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ]
        );

        return redirect('/purchase/' . $item_id);
    }

    /**
     * 商品の購入を確定し、履歴をデータベースに保存する
     */
    public function store(Request $request, $item_id)
    {
        $user = Auth::user();
        $profile = $user->profile;

        $addressError = null;
        if (!$profile || empty($profile->postal_code) || empty($profile->address)) {
            $addressError = '配送先住所が登録されていないため、購入できません。';
        }

        $rules = [
            'payment_method' => ['required', 'in:コンビニ払い,カード払い'],
        ];

        $messages = [
            'payment_method.required' => '支払い方法を選択してください。',
            'payment_method.in' => '正しい支払い方法を選択してください。',
        ];

        if ($addressError) {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $messages);
            $validator->errors()->add('address_error', $addressError);
            
            if (empty($request->payment_method)) {
                $validator->errors()->add('payment_method', '支払い方法を選択してください。');
            }

            return back()->withErrors($validator)->withInput();
        }

        $request->validate($rules, $messages);

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
