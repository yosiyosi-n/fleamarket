<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MypageController extends Controller
{
    /**
     * プロフィール画面（マイページ）を表示する
     */
    public function index()
    {
        // views/mypage/index.blade.php を読み込む指定に修正
        return view('mypage.index');
    }

    /**
     * プロフィール編集画面を表示する
     */
    public function edit()
    {
        // views/mypage/edit.blade.php を読み込む指定に修正
        return view('mypage.edit');
    }
}
