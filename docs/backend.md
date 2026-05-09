# 京都TECH学園祭 バックエンド定義書

## 1. 目的
- 学園祭アプリ全体のバックエンド方針をまとめる
- APIの詳細は [docs/api.md](api.md) に分離して管理する
- DB設計の詳細は [docs/database.md](database.md) を参照する

## 2. システム概要
- フロントエンド: Next.js
- バックエンド: Laravel
- データベース: MySQL
- API方式: REST API
- 通信形式: JSON
- 認証:
  - 来場者向け: なし
  - 店舗向け: JWT

## 3. バックエンドの責務
- 来場者向けの一覧表示、詳細表示、マップ表示を返す
- 店舗向けのログイン、待ち時間更新、注文管理を処理する
- データベースへ読み書きし、画面表示用のJSONを返す
- バリデーション、認証、認可、エラー処理を共通化する

## 4. API仕様の参照先
以下のAPI詳細は [docs/api.md](api.md) に記載する。

- [飲食店一覧取得](api.md#41-飲食店一覧取得)
- [飲食店詳細取得](api.md#42-飲食店詳細取得)
- [店舗ログイン](api.md#43-店舗ログイン)
- [待ち時間更新](api.md#44-待ち時間更新)
- [マップ表示データ取得](api.md#45-マップ表示データ取得)

## 5. ディレクトリ構成（Laravel想定）
```text
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   └── Store/
│   │   ├── Requests/
│   │   ├── Resources/
│   │   └── Middleware/
│   ├── Models/
│   ├── Services/
│   └── Actions/
├── routes/
│   ├── api.php
│   └── web.php
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── config/
├── tests/
│   ├── Feature/
│   └── Unit/
└── storage/
```

### 各ディレクトリの役割
- `app/Http/Controllers`: APIの入口。リクエストを受けてサービス層を呼ぶ
- `app/Http/Requests`: バリデーションルールをまとめる
- `app/Http/Resources`: レスポンスJSONの整形を行う
- `app/Http/Middleware`: 認証や権限チェックを行う
- `app/Models`: Eloquentモデルを置く
- `app/Services`: 待ち時間更新や注文管理などの業務ロジックを置く
- `app/Actions`: 単発の処理や再利用したい処理を置く
- `routes/api.php`: APIルートを定義する
- `database/migrations`: テーブル定義を管理する
- `database/seeders`: 初期データやテストデータを入れる
- `database/factories`: テスト用データを生成する
- `tests/Feature`: APIの結合テストを置く
- `tests/Unit`: 単体テストを置く

## 6. 開発時の分担イメージ
- Controller: ルーティング直後の受付とレスポンス返却
- Request: 入力チェック
- Service: ビジネスロジック
- Model: DBアクセス
- Resource: APIレスポンス整形
- Migration: DB設計変更
- Test: 仕様の確認

## 7. 認証・権限設計
- JWTの有効期限を設定する
- 店舗向けのAPIはログイン後のみ利用できる
- 管理者向け処理は店舗ID単位で認可チェックを行う
- 店舗向けAPIの詳細なログイン仕様は [docs/api.md](api.md) を参照する

## 8. エラー・バリデーション方針
- 入力チェックは `FormRequest` でまとめる
- 不正な入力は 400 Bad Request を返す
- 認証失敗は 401 Unauthorized を返す
- 権限不足は 403 Forbidden を返す
- 存在しないIDは 404 Not Found を返す
- 共通エラーフォーマットの詳細は [docs/api.md](api.md) を参照する

## 9. 非機能要件・運用
- 学園祭開催時間中は安定稼働を優先する
- 一覧取得は体感的にすぐ表示できる速度を目標とする
- ログイン成功・失敗、待ち時間更新、注文状態変更を記録する
- 5xxエラー率、API応答時間、DB接続失敗を監視する
- MySQLの定期バックアップを取得する
- APIはLaravel、フロントエンドはNext.js、DBはMySQLを前提に運用する
- 画面表示に失敗しても再読み込みで復帰できるようにする

## 10. セキュリティ考慮点
- 認証情報は平文で保存しない
- 店舗向けAPIはJWTの署名検証を必ず行う
- 管理画面からの操作は認可チェックを必ず行う
- SQLインジェクション対策としてORMまたはプレースホルダを使用する
- XSS対策として、表示時にHTMLをそのまま出力しない
- CSRF対策は管理画面の仕様に応じて有効化する
- `Authorization` ヘッダや秘密鍵をログに出力しない

## 11. 参考資料
- [API定義書](api.md)
- [データベース定義書](database.md)
- [フロントエンド定義書](frontend.md)
