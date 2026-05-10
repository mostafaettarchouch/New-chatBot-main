<?php
// setup_db.php
$dbPath = __DIR__ . '/database/database.sqlite';
$pdo = new PDO("sqlite:" . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    email TEXT UNIQUE,
    password TEXT
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS legal_procedures (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT,
    description TEXT,
    summary TEXT
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS keywords (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    legal_procedure_id INTEGER,
    word TEXT,
    weight INTEGER
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS questions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    text TEXT,
    confidence_score REAL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS unanswered_questions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    text TEXT,
    resolved INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Seed Admin
$password = password_hash('password123', PASSWORD_DEFAULT);
$pdo->exec("INSERT OR IGNORE INTO users (email, name, password) VALUES ('admin@example.com', 'Admin', '$password')");

// Seed Procedure
$stmt = $pdo->query("SELECT COUNT(*) FROM legal_procedures");
if ($stmt->fetchColumn() == 0) {
    $pdo->exec("INSERT INTO legal_procedures (title, description, summary) VALUES (
        'إصدار جواز سفر جديد',
        'يمكنك التقدم للحصول على جواز سفر جديد من خلال المقاطعة.',
        'جمع الوثائق، تقديم الطلب في المقاطعة.'
    )");
    $procedure_id = $pdo->lastInsertId();
    $pdo->exec("INSERT INTO keywords (legal_procedure_id, word, weight) VALUES ($procedure_id, 'جواز', 3)");
    $pdo->exec("INSERT INTO keywords (legal_procedure_id, word, weight) VALUES ($procedure_id, 'سفر', 2)");
}
echo "Database setup complete!\n";
