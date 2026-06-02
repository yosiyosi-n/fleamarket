<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 誰でもこのチェック機能を使えるようにします
    }

    public function rules(): array
    {
        return [
            // 💡 仕様書の条件：必須入力 ＆ 254文字まで（255文字以上は弾く）
            'comment' => ['required', 'string', 'max:254'],
        ];
    }

    public function messages(): array
    {
        return [
            'comment.required' => 'コメントを入力してください。',
            'comment.max' => 'コメントは254文字以内で入力してください。', // 仕様書の「255字以上は不可」に対応
        ];
    }
}
