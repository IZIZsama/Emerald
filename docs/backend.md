# 京都TECH学園祭 バックエンドAPI定義書

## 1. 概要
- 名称: 
- バージョン: v1
- 目的: 学園祭向けアプリのAPI
- 対象クライアント: （例: モバイルアプリ、管理画面）

## 2. 共通仕様
- ベースURL: `https://api.example.com/api/v1`
- データ形式: `application/json`
- 日時形式: ISO 8601（例: `2026-05-09T12:34:56Z`）
- 認証:
  - 来場者: なし
  - 管理者: `Authorization: Bearer <JWT>`
- レート制限: 1000 req/min
- バージョニング方式: URLパス `/v1/`

## 3. エンドポイント一覧（概要）
| エンドポイント | メソッド | 説明 |
| --- | ---: | --- |
| /restaurants | GET | 飲食店一覧取得 |
| /restaurants/{id} | GET | 飲食店の詳細と待ち時間・受け取り可能番号一覧表示 |
| /store/login | POST | 店舗ログイン（JWT発行） |
| /store/{id}/wait-time | PATCH | 待ち時間更新 |
| /map/facilities | GET | マップ表示データ取得 |

## 4. エンドポイント詳細テンプレート
id下3桁を教室番号にしたっていい

### 飲食店一覧取得 
— `GET /restaurants`
- 説明: 来場者向けに、飲食店一覧表示のapi
- 権限: anonymous
- パスパラメータ:なし
- クエリパラメータ:なし
- リクエスト例:
``` url
GET /api/v1/restaurants
Host: api.example.com
Accept: application/json
```
- レスポンス例:
```json
{
  "data": [
    {
      "id": "store-101",
      "name": "KTCカフェ",
      "is_open": true,
      "wait_time": 10
    },
    {
      "id": "store-102",
      "name": "天津飯",
      "is_open": true,
      "wait_time": 15
    },...
  ],
  "meta": {
    "total": 10
  }
}
```
- エラー例:
  - 500 Internal Server Error — 一覧取得処理で予期しないエラーが発生した場合

### 飲食店詳細取得
— `GET /restaurants/{id}`
- 説明: 来場者向けに、飲食店の詳細、待ち時間、受け取り可能番号一覧を取得するAPI
- 権限: anonymous
- パスパラメータ:
  - `id` (string) - 飲食店ID
- クエリパラメータ:なし
- リクエスト例:
``` url
GET /api/v1/restaurants/store-101
Host: api.example.com
Accept: application/json
```
- レスポンス例:
```json
{
  "id": "store-101",
  "name": "KTCカフェ",
  "description": "学園祭限定メニューを提供するカフェ",
  "is_open": true,
  "wait_time": 10,
  "ticket_numbers": ["A-120", "A-121", "A-122"]
}
```
- エラー例:
  - 404 Not Found — 指定した飲食店IDが存在しない場合
  - 500 Internal Server Error — 詳細情報の取得処理で予期しないエラーが発生した場合

### 店舗ログイン
— `POST /store/login`
- 説明: 店舗IDとパスワードでログインし、JWTを発行するAPI
- 権限: anonymous
- パスパラメータ:なし
- クエリパラメータ:なし
- リクエスト例:
``` url
POST /api/v1/store/login
Host: api.example.com
Content-Type: application/json
Accept: application/json
```
- リクエストボディ例:
```json
{
  "login_id": "cafe_admin",
  "password": "password123"
}
```
- レスポンス例:
```json
{
  "token": "eyJhbGciOi...",
  "store_id": "store-101"
}
```
- エラー例:
  - 400 Bad Request — login_id または password が不足している場合
  - 401 Unauthorized — 店舗IDまたはパスワードが正しくない場合

### 待ち時間更新
— `PATCH /store/{id}/wait-time`
- 説明: 店舗側が待ち時間や待ち人数を更新するAPI
- 権限: store-owner
- パスパラメータ:
  - `id` (string) - 店舗ID
- クエリパラメータ:なし
- リクエスト例:
``` url
PATCH /api/v1/store/store-101/wait-time
Host: api.example.com
Content-Type: application/json
Accept: application/json
Authorization: Bearer <JWT_TOKEN>
```
- リクエストボディ例:
```json
{
  "current_wait_min": 20,
  "current_queue_count": 10
}
```
- レスポンス例:
```json
{
  "id": "store-101",
  "current_wait_min": 20,
  "current_queue_count": 10,
  "updated_at": "2026-05-09T12:34:56Z"
}
```
- エラー例:
  - 400 Bad Request — current_wait_min や current_queue_count の値が不正な場合
  - 401 Unauthorized — JWTが無効または期限切れの場合
  - 403 Forbidden — 自店舗以外の待ち時間を更新しようとした場合
  - 404 Not Found — 指定した店舗IDが存在しない場合

### マップ表示データ取得
現状はっきりと決まっていないため画像でもいいかも

— `GET /map/facilities`
- 説明: 校内マップに表示する施設・ブース情報を取得するAPI
- 権限: anonymous
- パスパラメータ:なし
- クエリパラメータ:なし
- リクエスト例
``` url
GET /api/v1/map/facilities
Host: api.example.com
Accept: application/json
```
- レスポンス例:
```json
{
  "data": [
    {
      "id": "booth-001",
      "name": "VRお化け屋敷",
      "x": 120,
      "y": 80
    },
    {
      "id": "facility-001",
      "name": "本部",
      "x": 50,
      "y": 30
    }
  ]
}
```
- エラー例:
  - 500 Internal Server Error — マップ表示データの取得に失敗した場合

## 5. データモデル（主要なリソース）
database参照

## 6. 認証・権限設計
- JWTの有効期限: 例 24時間
- 発行フロー: `POST /store/login` → `token` を返却
- トークン検証: 署名アルゴリズム、リフレッシュの有無

## 7. エラー仕様
- 共通エラーフォーマット:
```json
{
  "error": {
    "code": "INVALID_PARAMS",
    "message": "パラメータが不正です",
    "details": { "field": "quantity", "reason": "must be > 0" }
  }
}
```
- HTTPステータスと例:
  - 400 Bad Request — パラメータ不足
  - 401 Unauthorized — トークン無効/期限切れ
  - 403 Forbidden — 権限不足
  - 404 Not Found — リソース未発見
  - 429 Too Many Requests — レート超過
  - 500 Internal Server Error — サーバーエラー

## 8. バリデーションルール
- `restaurants/{id}` の `id`: 必須, string, 存在する店舗IDであること
- `store/login` の `login_id`: 必須, string, 1文字以上
- `store/login` の `password`: 必須, string, 1文字以上
- `store/{id}/wait-time` の `current_wait_min`: 必須, integer, 0以上999以下
- `store/{id}/wait-time` の `current_queue_count`: 必須, integer, 0以上999以下
- `map/facilities`: 入力値なし, 取得失敗時はエラーを返す
- 共通ルール:
  - 未入力項目がある場合は 400 Bad Request を返す
  - 型が不正な場合は 400 Bad Request を返す
  - 存在しないIDの場合は 404 Not Found を返す

## 9. 非機能要件・運用
- 可用性: 学園祭開催時間中は安定稼働を優先する
- 応答速度: 一覧取得は体感的にすぐ表示できる速度を目標とする
- ログ: ログイン成功・失敗、待ち時間更新、注文状態変更を記録する
- 監視: 5xxエラー率、API応答時間、DB接続失敗を監視する
- バックアップ: MySQLの定期バックアップを取得する
- デプロイ: APIはLaravel、フロントエンドはNext.js、DBはMySQLを前提に運用する
- 障害時対応: 画面表示に失敗しても再読み込みで復帰できるようにする

## 10. セキュリティ考慮点
- 認証情報は平文で保存しない
- 店舗向けAPIはJWTの署名検証を必ず行う
- 管理画面からの操作は認可チェックを必ず行う
- SQLインジェクション対策としてORMまたはプレースホルダを使用する
- XSS対策として、表示時にHTMLをそのまま出力しない
- CSRF対策は管理画面の仕様に応じて有効化する
- `Authorization` ヘッダや秘密鍵をログに出力しない
