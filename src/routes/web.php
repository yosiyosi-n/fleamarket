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

// ==========================================
// 1. ログイン前（ゲスト）でも全員が見られるルート
// ==========================================
Route::get('/', [ItemController::class, 'index'])->name('item.index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

// ==========================================
// 2. ログイン必須（auth）、かつ【メール認証も完了している人】専用のルート
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {
    
    // 出品関連
    Route::get('/sell', [ItemController::class, 'create'])->name('item.create');
    Route::post('/sell', [ItemController::class, 'store']);
    Route::delete('/item/{item_id}/delete', [ItemController::class, 'destroy']);
    
    // コメント・いいね
    Route::post('/item/{item_id}/comment', [ItemController::class, 'storeComment']);
    Route::post('/item/{item_id}/like', [ItemController::class, 'toggleLike']);
    
    // 購入手続き・確定
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'index']);
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store']);
    
    // マイページトップ（購入・出品一覧タブ含む）
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
});

// ==========================================
// 3. ログイン必須（auth）だが【メール認証が未完了】でも特別に入れるルート
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // 認証メール誘導画面
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');
    Route::post('/email/verification-notification', [\Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController::class, 'store'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');

    // 認証完了の着陸先 ＆ 住所変更用ルート
    Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.edit');
    Route::post('/mypage/profile', [MypageController::class, 'update']);
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('purchase.address');
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress']);
});
