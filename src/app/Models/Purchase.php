<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    // ⬇︎ 【★ここを追加！】データベースへの保存を許可する項目を並べます ⬇︎
    protected $fillable = [
        'user_id',
        'item_id',
        'payment_method',
        'postal_code',
        'address',
        'building',
    ];
}