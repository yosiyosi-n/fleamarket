<?php

namespace App\Http\Requests;

use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;

class LoginRequest extends FortifyLoginRequest
{
    /**
     * チェック機能を誰にでも許可
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * ログインに必要な入力欄のチェックルール
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * 画面に表示する日本語のエラーメッセージ
     */
    public function messages(): array
    {
        return [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => 'メールアドレスの形式が正しくありません。',
            'password.required' => 'パスワードを入力してください。',
            'email.failed' => 'ログイン情報が登録されていません。',
        ];
    }
}
