<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * 商品一覧画面（トップ画面）を表示する
     */
    public function index()
    {
        // views/item/index.blade.php を画面に表示しなさいという命令
        return view('item.index');
    }

    /**
     * 商品詳細画面を表示する
     */
    public function show($item_id)
    {
        return view('item.show');
    }

    /**
     * 商品出品画面を表示する
     */
    public function create()
    {
        return view('item.create');
    }
}
