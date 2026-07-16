<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comment' => ['required', 'string', 'max:254'],
        ];
    }

    public function messages(): array
    {
        return [
            'comment.required' => 'コメントを入力してください。',
            'comment.max' => 'コメントは254文字以内で入力してください。',
        ];
    }
}
