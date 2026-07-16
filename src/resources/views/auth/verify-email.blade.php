<x-app-layout>
    <x-slot name="title">メール認証の確認 - フリマアプリ</x-slot>

    <div style="max-width: 550px; margin: 180px auto; padding: 40px; text-align: center;">
        <h2 style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 50px;">登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
        </h2>
        
        <!-- メールボックス（MailHog）へ移動するボタン -->
        <a href="http://localhost:8025" target="_blank" style="display: block; width: 50%; margin: 0 auto 20px auto; text-align: center; background-color: #c4cacf; color: black; text-decoration: none; padding: 15px; border-radius: 4px; font-size: 16px; font-weight: bold; box-shadow: 0 2px 5px rgba(0,0,0,0.1); box-sizing: border-box; transition: background-color 0.2s;">
            認証はこちらから
        </a>

        <!-- 認証メール再送フォーム -->
        <form method="POST" action="/email/verification-notification" style="margin-bottom: 20px;">
            @csrf
            <button type="submit" style="color: blue; background: none; border: none; padding: 12px 24px; border-radius: 4px; font-size: 12px; cursor: pointer; width: 50%; box-sizing: border-box;">
                認証メールを再送する
            </button>
        </form>

        @if (session('status') == 'verification-link-sent')
            <div style="color: #28a745; font-size: 14px; font-weight: bold; margin-top: 10px; background-color: #e2f0d9; padding: 10px; border-radius: 4px; border: 1px solid #bcdca7;">
                新しい認証メールを再送信しました！再度メールボックスをご確認ください。
            </div>
        @endif
    </div>
</x-app-layout>
