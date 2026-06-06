# データベースER図

`docs/database.md` のテーブル定義と、`README.md` / `docs/backend.md` の構成をもとにした ER 図です。

```mermaid
erDiagram
    STORES {
        string id PK
        string name
        string description
        boolean is_open
        int current_wait_min
        int current_queue_count
        datetime created_at
        datetime updated_at
    }

    MENU_ITEMS {
        string id PK
        string store_id FK
        string name
        string description
        int price
        boolean is_available
        datetime created_at
        datetime updated_at
    }

    ORDERS {
        string id PK
        string store_id FK
        string ticket_number
        int total_price
        string status
        datetime ordered_at
        datetime called_at
        datetime served_at
        datetime created_at
        datetime updated_at
    }

    ORDER_ITEMS {
        string id PK
        string order_id FK
        string menu_item_id FK
        int quantity
        int unit_price
        int subtotal
    }

    STORE_ACCOUNTS {
        string id PK
        string store_id FK
        string login_id
        string password_hash
        datetime created_at
        datetime updated_at
    }

    MAP_FACILITIES {
        string id PK
        string store_id FK
        string name
        string type
        int floor
        int x
        int y
        datetime created_at
        datetime updated_at
    }

    STORES ||--o{ MENU_ITEMS : has
    STORES ||--o{ ORDERS : receives
    ORDERS ||--o{ ORDER_ITEMS : contains
    MENU_ITEMS ||--o{ ORDER_ITEMS : referenced_by
    STORES ||--o{ STORE_ACCOUNTS : owns
    STORES ||--o{ MAP_FACILITIES : displays
```

## 補足

- `stores` は店舗・ブースの基本情報と待ち時間を持つ
- `orders` と `order_items` でモバイルオーダーの注文内容を表す
- `store_accounts` は店舗ログイン用の認証情報を表す
- `map_facilities` は校内マップ上に出す施設・ブース位置を表す
