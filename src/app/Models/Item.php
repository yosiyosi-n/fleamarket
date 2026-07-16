<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // データベースへの保存を許可する項目
    protected $fillable = [
        'user_id',
        'name',
        'brand',
        'description',
        'condition',
        'price',
        'image_path',
    ];

    /**
     *  カテゴリとの多対多リレーション
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item', 'item_id', 'category_item_id');
    }

    /**
     *  いいね機能との1対多リレーション
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     *  コメント機能との1対多リレーション
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     *  購入履歴との1対多リレーション
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
