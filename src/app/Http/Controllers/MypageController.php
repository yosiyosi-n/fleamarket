<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
        /**
     * プロフィール画面（マイページ）を表示する
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        $page = $request->query('page', 'sell');

        $items = collect();

        if ($page === 'sell') {
            $items = \App\Models\Item::where('user_id', $user->id)->latest()->get();
        } elseif ($page === 'buy') {
            $items = \App\Models\Item::whereHas('purchases', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->latest()->get();
        }

        return view('mypage.index', compact('user', 'profile', 'page', 'items'));
    }

    /**
     * プロフィール編集画面を表示する
     */
    public function edit()
    {
        $user = Auth::user();

        $profile = $user->profile;

        return view('mypage.edit', compact('user', 'profile'));
    }

        /**
     * プロフィール情報を更新する
     */
    public function update(\App\Http\Requests\ProfileRequest $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        $path = $profile->image_path ?? null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('profiles', 'public');
        }

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

        return redirect('/mypage');
    }

}
