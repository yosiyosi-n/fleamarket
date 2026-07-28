<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HelloTest extends TestCase
{
    use RefreshDatabase;

    public function testHello()
    {
        // 💡 1. 事前にテスト用のユーザーをデータベースに作成する
        $user = User::create([
            'name' => 'aaa',
            'email' => 'bbb@ccc.com',
            'password' => Hash::make('test12345'), // パスワードを暗号化して保存
        ]);

        // 2. ログインを試みる
        $response = $this->post('/login', [
            'email' => 'bbb@ccc.com',
            'password' => 'test12345',
        ]);

        // 3. 正しくログインできて、指定のページに遷移したかを検証
        // 💡 もしトップページに遷移する仕様なら '/' に変更してください
        $response->assertRedirect('/');
        
        // 4. ユーザーが認証状態（ログイン済み）になっているか検証
        $this->assertAuthenticatedAs($user);
    }
}
