<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // ⬇︎ 【★ここを追加！】データベースへの保存を許可する項目を並べます ⬇︎
    protected $fillable = [
        'user_id',
        'item_id',
        'comment',
    ];

    /**
     * 💡 ついでに：コメントしたユーザーの情報を取得するためのリレーション設定
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
