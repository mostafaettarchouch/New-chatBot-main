<?php
$pdo = new PDO('sqlite:backend/database/database.sqlite');
$pdo->exec("CREATE TABLE IF NOT EXISTS personal_access_tokens (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id INTEGER NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL,
    abilities TEXT,
    last_used_at DATETIME,
    expires_at DATETIME,
    created_at DATETIME,
    updated_at DATETIME
)");
$pdo->exec("CREATE UNIQUE INDEX IF NOT EXISTS personal_access_tokens_token_unique ON personal_access_tokens (token)");
echo "Table personal_access_tokens created successfully.\n";
