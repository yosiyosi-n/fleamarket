<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExposeRequest extends FormRequest
{
    /**
     * このチェック機能を誰にでも許可するか
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 仕様書15番の項目をチェックするバリデーションルール
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'condition' => ['required', 'string'],
            'price' => ['required', 'integer', 'min:300', 'max:9999999'],
            'categories' => ['required', 'array', 'min:1'], // 最低1つ以上のカテゴリ選択を必須にします
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // 画像ファイルを必須にします
            'brand' => ['nullable', 'string', 'max:255'], // ブランド名は任意（空欄OK）
        ];
    }

    /**
     * 画面に表示する丁寧な日本語エラーメッセージ
     */
    public function messages(): array
    {
        return [
            'name.required' => '商品名を入力してください。',
            'description.required' => '商品の説明を入力してください。',
            'condition.required' => '商品の状態を選択してください。',
            'price.required' => '販売価格を入力してください。',
            'price.integer' => '販売価格は数値で入力してください。',
            'price.min' => '販売価格は300円以上で入力してください。',
            'price.max' => '販売価格は9,999,999円以下で入力してください。',
            'categories.required' => 'カテゴリーを1つ以上選択してください。',
            'image.required' => '商品画像を選択してください。',
            'image.image' => '指定されたファイルが画像ではありません。',
        ];
    }
}
