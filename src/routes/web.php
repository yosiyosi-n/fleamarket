<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MypageController;

/*

|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. 商品関連（ItemController）
Route::get('/', [ItemController::class, 'index'])->name('item.index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

// 💡 修正：重複を解消し、名前付きルートとログイン制限（auth）を綺麗に合流させました
Route::get('/sell', [ItemController::class, 'create'])->name('item.create')->middleware('auth');
Route::post('/sell', [ItemController::class, 'store'])->middleware('auth');

Route::post('/item/{item_id}/comment', [ItemController::class, 'storeComment'])->middleware('auth');
Route::delete('/item/{item_id}/delete', [ItemController::class, 'destroy']);

// 💡 修正：抜けていた「いいね機能」のルートをここに追加しました
Route::post('/item/{item_id}/like', [ItemController::class, 'toggleLike'])->middleware('auth');


// 2. 購入関連（PurchaseController）
Route::get('/purchase/{item_id}', [PurchaseController::class, 'index'])->middleware('auth');
Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('purchase.address')->middleware('auth');
// ⬇︎ 【★ここを追加！】送付先住所の変更処理（保存・上書き） ⬇︎
Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->middleware('auth');
// ⬇︎ 【★ここを追加！】商品購入確定処理（データベース保存） ⬇︎
Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->middleware('auth');


// 3. プロフィール関連（MypageController）
// 💡 修正：マイページ関連も、未ログイン時のクラッシュを防ぐために鍵（auth）を付けました
Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index')->middleware('auth');
Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.edit')->middleware('auth');
Route::post('/mypage/profile', [MypageController::class, 'update'])->middleware('auth');