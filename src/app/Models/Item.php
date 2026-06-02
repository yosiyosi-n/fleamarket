<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

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
     * ⬇︎ 【★ここを追加！】商品に紐づく複数のカテゴリを取得する設定（多対多） ⬇︎
     */
    public function categories()
    {
        // 先ほど作成した「category_item」中間テーブルを介して、Categoryモデルと結びつけます
        return $this->belongsToMany(Category::class, 'category_item', 'item_id', 'category_item_id');
    }

    /**
     * ⬇︎ 【★ついでにここも追加！】仕様書4番の「Sold表示」でエラーにならないための購入履歴リレーション ⬇︎
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}