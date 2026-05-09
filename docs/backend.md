# 京都TECH学園祭 バックエンドAPI定義書

本文書は、学園祭来場者向けアプリおよび店舗向け管理画面がバックエンドと通信するためのREST API仕様を定義します。

## 1. 共通仕様

- ベースURL: `https://api.kyoto-tech-fes.example.com/v1`
- データ形式: `JSON` (`Content-Type: application/json`)
- 認証:
	- 来場者向け: 認証なし（または匿名Auth）
	- 店舗向け: `Authorization: Bearer <JWT_TOKEN>`

## 2. 来場者向けAPI

### 2.1. ブース・待ち時間 (Attractions)

#### `GET /attractions`

ブースの一覧と現在の待ち時間を取得します。

**Response**

```json
[
	{
		"id": "attr-001",
		"name": "VRお化け屋敷",
		"area": "3F 301教室",
		"category": "thrill",
		"wait_time": 15,
		"is_open": true,
		"tags": ["スリル系", "身長制限あり"]
	}
]
```

### 2.2. モバイルオーダー (Restaurants)

#### `GET /restaurants`

注文可能な店舗一覧を取得します。

#### `GET /restaurants/{store_id}/menu`

特定の店舗のメニュー一覧を取得します。

#### `POST /orders`

カートの内容を送信し、注文を確定します。

**Request Body**

```json
{
	"store_id": "store-101",
	"items": [
		{ "menu_item_id": "m-001", "quantity": 2 },
		{ "menu_item_id": "m-005", "quantity": 1 }
	]
}
```

**Response: `201 Created`**

```json
{
	"order_id": "ord-999",
	"ticket_number": "A-124",
	"status": "pending"
}
```

#### `GET /orders/{order_id}/status`

注文の最新ステータス（準備中、呼び出し中、完了）を確認します。

### 2.3. マップ (Map)

#### `GET /map/facilities`

校内マップに表示する施設・ブースの座標データを取得します。

## 3. 店舗管理向けAPI

### 3.1. 認証 (Auth)

#### `POST /store/login`

店舗IDとパスワードでログインし、トークンを取得します。

**Request**

```json
{ "login_id": "cafe_admin", "password": "..." }
```

**Response**

```json
{ "token": "...", "store_id": "store-101" }
```

### 3.2. ステータス管理 (Management)

#### `PATCH /store/{store_id}/wait-time`

待ち時間や待ち人数を手動で更新します。

**Request**

```json
{ "current_wait_min": 20, "current_queue_count": 10 }
```

#### `GET /store/{store_id}/orders`

店舗に届いている注文一覧（提供待ち、呼び出し中など）を取得します。

#### `PATCH /orders/{order_id}/status`

注文ステータスを変更します（例：`pending -> calling`）。

**Request**

```json
{ "status": "calling" }
```

### 3.3. ダッシュボード (Analytics)

#### `GET /store/{store_id}/stats`

本日の総売上、注文数、平均待ち時間などの統計を取得します。

## 4. エラーレスポンス

標準的なHTTPステータスコードを使用します。

| コード | 意味 | 原因の例 |
| --- | --- | --- |
| 400 | Bad Request | リクエストパラメータの不足、在庫不足での注文 |
| 401 | Unauthorized | 店舗ログイン情報の誤り、トークンの期限切れ |
| 404 | Not Found | 存在しない店舗IDや注文ID |
| 500 | Internal Server Error | サーバー側の予期せぬエラー |
