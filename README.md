# フリマアプリ (fleamarket)

応用学習タームの成果物、個人間の商品出品・購入・管理ができる「フリマアプリ」です。

## 👥 画面定義一覧

### 🛍️ 商品・購入関連
- **商品一覧画面（トップ画面）:** `/` (おすすめ / マイリスト（いいね）の2大タブ切り替え仕様)
- **商品詳細画面:** `/item/{item_id}` (※自分の出品物の場合は「出品取り消しボタン」へ自動変身)
- **商品購入手続き画面:** `/purchase/{item_id}` (支払い方法と住所の二重防衛バリデーション搭載)
- **送付先住所変更画面:** `/purchase/address/{item_id}` (購入手続きに連動したお届け先更新)
- **商品出品画面:** `/sell` (プレースホルダーを内包した美しいドラッグ＆ドロップ風デザイン)

### 👤 マイページ・設定関連
- **プロフィール画面:** `/mypage` (出品した商品 / 購入した商品の4列折り返しグリッド仕様)
- **プロフィール編集画面:** `/mypage/profile` (角丸の赤いカスタム画像選択ボタン仕様)

### 🔐 認証・裏側処理関連
- **会員登録画面 / ログイン画面:** `/register` / `/login`
- **メール認証誘導画面 (応用仕様):** `/email/verify` (再送ボタン・MailHog直行ボタン付きの洗練されたUI)
- **コメント投稿処理 / いいねトグル処理:** `/item/{item_id}/comment` / `/item/{item_id}/like`
- **出品取り消し処理（削除）:** `/item/{item_id}/delete` (DELETEメソッドによる完全安全設計)

---

## ✨ 追加機能

1. **「複数エラーの一画面同時バリデーションロジック」**
   購入手続き時、支払い方法と配送先住所の両方に不備がある場合、Laravelの標準バリデーション（Validatorファクトリ）を独自に拡張。処理を途中でせき止めず、**「選択欄の赤枠化＋その下への赤文字」と「配送先住所の下への赤文字」を一画面に同時にパッと表示させる親切なUX**を実装しました。
2. **「出品者モード（自己購入ガード）の自動出し分け」**
   マイページから自分の出品した商品を開いた際は、システムが自動的に出品者ID（`user_id`）とログインIDを照合。いいね・コメント・購入ボタンを非表示にし、代わりに**誤操作を防ぐ確認ポップアップ（confirm）付きの「出品を取り消す」ボタン**へと動的に画面を切り替えます。
3. **「MailHog連動型メール認証＆ログイン状態に応じたリダイレクト制御」**
   新規会員登録時に堅牢なメール認証（`MustVerifyEmail`）を強制。認証メール内のリンクをクリックした直後は、**購入前必須アクションとして「プロフィール設定画面（`/mypage/profile`）」へ初回限定で自動直行**させます。一方で、すでに認証済みの既存ユーザーが普通にログインした際は、邪魔をせずスムーズに**「ログイン状態のホーム（トップページ）」**へ繋ぐ高度なレスポンス出し分けロジックを実装しました。

---

## 🚀 環境構築手順 (Docker環境版)

1. **リポジトリのクローンと移動**
   ```bash
   git clone https://github.com/yosiyosi-n/fleamarket.git
   cd fleamarket
   ```

2. **Dockerコンテナの起動**
   ```bash
   docker-compose up -d
   ```

3. **コンテナ内部での初期設定** (以下、コンテナ内で実行)
   ```bash
   docker-compose exec php bash
   composer install
   cp .env.example .env
   ```
   ※ `.env` のデータベース（DB_HOST=mysql）とメール（MAIL_HOST=mailhog）の設定を修正してください。

   ```env
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_DATABASE=laravel_db
   DB_USERNAME=root
   DB_PASSWORD=root

   MAIL_MAILER=smtp
   MAIL_HOST=mailhog
   MAIL_PORT=1025
   MAIL_FROM_ADDRESS="noreply@fleamarket.com"
   MAIL_FROM_NAME="フリマアプリ運営事務局"
   ```

   ```bash
   php artisan key:generate
   php artisan migrate:fresh --seed
   php artisan optimize:clear
   exit
   ```
---
**動作確認**: [http://localhost](http://localhost) (アプリ) / [http://localhost:8025](http://localhost:8025) (MailHog)

## 📊 データベース設計 (ER図)
ルート直下の `fleamarket.drawio.png` を参照。
