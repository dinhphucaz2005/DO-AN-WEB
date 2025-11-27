# TEST CASES (Ngắn gọn)

Tài liệu này chứa các test case thủ công ngắn gọn, dễ đọc, dùng cho QA nhanh.

---

erDiagram
    USERS {
        bigint id PK
        string name
        string email UNIQUE
        timestamp email_verified_at
        string password
        string remember_token
        boolean is_admin
        timestamp created_at
        timestamp updated_at
    }

    MEMES {
        bigint id PK
        bigint user_id FK
        string title
        enum type
        blob image_data
        string mime_type
        text description
        json data
        boolean is_public
        timestamp created_at
        timestamp updated_at
    }

    LIKES {
        bigint id PK
        bigint user_id FK
        bigint meme_id FK
        timestamp created_at
        timestamp updated_at
    }

    COMMENTS {
        bigint id PK
        bigint user_id FK
        bigint meme_id FK
        text content
        timestamp created_at
        timestamp updated_at
    }

    PASSWORD_RESET_TOKENS {
        string email PK
        string token
        timestamp created_at
    }

    SESSIONS {
        string id PK
        bigint user_id FK NULLABLE
        string ip_address
        text user_agent
        longtext payload
        integer last_activity
    }

    USERS ||--o{ MEMES : "creates"
    USERS ||--o{ LIKES : "likes"
    MEMES  ||--o{ LIKES : "has"
    USERS ||--o{ COMMENTS : "writes"
    MEMES  ||--o{ COMMENTS : "has"
    USERS ||--o{ SESSIONS : "may have"

---

