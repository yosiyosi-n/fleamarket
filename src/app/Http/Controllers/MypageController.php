<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    /**
     * プロフィール画面（マイページ）を表示する
     */
    public function index()
    {
        return view('mypage.index');
    }

    /**
     * プロフィール編集画面を表示する（仕様書14番対応）
     */
    public function edit()
    {
        // 💡 1. ログイン中のユーザー情報（ユーザー名やメールアドレス）を取得
        $user = Auth::user();

        // 💡 2. 過去に設定された住所や画像データ（プロフィール）を取得
        $profile = $user->profile;

        // 💡 3. これまでの画面と同じおなじみの流れで、データをセットにしてHTMLへ届けます！
        return view('mypage.edit', compact('user', 'profile'));
    }

        /**
     * プロフィール情報を更新する（仕様書14番の裏側処理）
     */
    public function update(\App\Http\Requests\ProfileRequest $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        // 💡 1. 画像が新しくアップロードされた場合の処理
        $path = $profile->image_path ?? null; // 過去の画像をデフォルトにする
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('profiles', 'public');
        }

        // 💡 2. プロフィールテーブル（profiles）を上書き更新
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $request->name,
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
                'image_path' => $path,
            ]
        );

        // 💡 3. 保存がすべて完了したら、仕様書通りの遷移先であるマイページ（/mypage）へ送ります！
        return redirect('/mypage');
    }

}
