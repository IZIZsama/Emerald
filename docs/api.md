# バックエンドAPI一覧

## 共通仕様
- ベースURL: `https://api.example.com/api/v1`
- データ形式: `application/json`
- 認証:
	- 来場者: なし
	- 管理者: `Authorization: Bearer <JWT>`

## 飲食店一覧
``` url
GET /api/v1/restaurants
Host: api.example.com
Accept: application/json
```
### 説明
来場者向けに、注文可能な飲食店一覧を取得します。

### クエリパラメータ
| 項目 | 型 | 必須 | 説明 |
| --- | --- | --- | --- |
| page | int | 任意 | ページ番号 |
| limit | int | 任意 | 1ページあたりの件数 |

### 成功レスポンス (200)
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
		}
	],
	"meta": {
		"total": 10
	}
}
```

### エラー例
- 500 Internal Server Error: 一覧取得処理で予期しないエラーが発生した場合

## 飲食店詳細
``` url
GET /api/v1/restaurants/{id}
Host: api.example.com
Accept: application/json
```
### 説明
来場者向けに、飲食店の詳細、待ち時間、受け取り番号一覧を取得します。

### パスパラメータ
| 項目 | 型 | 必須 | 説明 |
| --- | --- | --- | --- |
| id | string | 必須 | 飲食店ID |

### 成功レスポンス (200)
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

### エラー例
- 404 Not Found: 指定した飲食店IDが存在しない場合
- 500 Internal Server Error: 詳細情報の取得処理で予期しないエラーが発生した場合

## 店舗ログイン
``` url
POST /api/v1/store/login
Host: api.example.com
Content-Type: application/json
Accept: application/json
```
### 説明
店舗IDとパスワードでログインし、JWTを発行します。

### リクエストボディ
```json
{
	"login_id": "cafe_admin",
	"password": "password123"
}
```

### 成功レスポンス (200)
```json
{
	"token": "eyJhbGciOi...",
	"store_id": "store-101"
}
```

### エラー例
- 400 Bad Request: login_id または password が不足している場合
- 401 Unauthorized: 店舗IDまたはパスワードが正しくない場合

## 待ち時間更新
``` url
PATCH /api/v1/store/{id}/wait-time
Host: api.example.com
Content-Type: application/json
Accept: application/json
Authorization: Bearer <JWT_TOKEN>
```
### 説明
店舗側が待ち時間や待ち人数を更新します。

### パスパラメータ
| 項目 | 型 | 必須 | 説明 |
| --- | --- | --- | --- |
| id | string | 必須 | 店舗ID |

### リクエストボディ
```json
{
	"current_wait_min": 20,
	"current_queue_count": 10
}
```

### 成功レスポンス (200)
```json
{
	"id": "store-101",
	"current_wait_min": 20,
	"current_queue_count": 10,
	"updated_at": "2026-05-09T12:34:56Z"
}
```

### エラー例
- 400 Bad Request: current_wait_min や current_queue_count の値が不正な場合
- 401 Unauthorized: JWTが無効または期限切れの場合
- 403 Forbidden: 自店舗以外の待ち時間を更新しようとした場合
- 404 Not Found: 指定した店舗IDが存在しない場合

## マップ表示データ取得
``` url
GET /api/v1/map/facilities
Host: api.example.com
Accept: application/json
```
### 説明
校内マップに表示する施設・ブース情報を取得します。

### 成功レスポンス (200)
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

### エラー例
- 500 Internal Server Error: マップ表示データの取得に失敗した場合

## エラー仕様
### 共通エラーフォーマット
```json
{
	"error": {
		"code": "INVALID_PARAMS",
		"message": "パラメータが不正です",
		"details": {
			"field": "quantity",
			"reason": "must be > 0"
		}
	}
}
```

### HTTPステータスと例
- 400 Bad Request: パラメータ不足
- 401 Unauthorized: トークン無効/期限切れ
- 403 Forbidden: 権限不足
- 404 Not Found: リソース未発見
- 429 Too Many Requests: レート超過
- 500 Internal Server Error: サーバーエラー
