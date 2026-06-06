<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // 画像は任意（空でもOK）にします
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'ユーザー名を入力してください。',
            'postal_code.required' => '郵便番号を入力してください。',
            'address.required' => '住所を入力してください。',
            'image.image' => '指定されたファイルが画像ではありません。',
        ];
    }
}
