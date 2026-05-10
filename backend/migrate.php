<?php
/**
 * Direct Database Migration Script
 * Executes migrations without Laravel/Artisan
 * Usage: php migrate.php
 */

$config = [
    'host' => 'localhost',
    'database' => 'legal_chatbot',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
];

try {
    $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "[✓] Connected to database: {$config['database']}\n\n";
    
    // Check if migrations table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'migrations'");
    if ($stmt->rowCount() === 0) {
        echo "[*] Creating migrations table...\n";
        $pdo->exec("
            CREATE TABLE migrations (
                id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                migration VARCHAR(255) NOT NULL UNIQUE,
                batch INT NOT NULL,
                created_at TIMESTAMP NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "[✓] Migrations table created\n\n";
    } else {
        echo "[✓] Migrations table exists\n\n";
    }
    
    // Get existing migrations
    $stmt = $pdo->query("SELECT migration FROM migrations");
    $executedMigrations = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'migration');
    
    // Define all migrations
    $migrations = [
        '2025_05_08_000001_create_languages_table' => [
            "CREATE TABLE IF NOT EXISTS languages (
                id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(255) NOT NULL UNIQUE,
                code VARCHAR(5) NOT NULL UNIQUE,
                is_default BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "INSERT INTO languages (name, code, is_default, created_at, updated_at) 
             VALUES ('العربية', 'ar', TRUE, NOW(), NOW()) 
             ON DUPLICATE KEY UPDATE updated_at = NOW()"
        ],
        '2025_05_08_000002_create_legal_categories_table' => [
            "CREATE TABLE IF NOT EXISTS legal_categories (
                id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(255) NOT NULL,
                description TEXT,
                language_id BIGINT UNSIGNED NOT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        ],
        '2025_05_08_000003_create_legal_procedures_table' => [
            "CREATE TABLE IF NOT EXISTS legal_procedures (
                id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                summary TEXT,
                legal_category_id BIGINT UNSIGNED NOT NULL,
                language_id BIGINT UNSIGNED NOT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (legal_category_id) REFERENCES legal_categories(id) ON DELETE CASCADE,
                FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        ],
        '2025_05_08_000004_create_procedure_steps_table' => [
            "CREATE TABLE IF NOT EXISTS procedure_steps (
                id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                legal_procedure_id BIGINT UNSIGNED NOT NULL,
                step_number INT UNSIGNED NOT NULL,
                description TEXT NOT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (legal_procedure_id) REFERENCES legal_procedures(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        ],
        '2025_05_08_000005_create_keywords_table' => [
            "CREATE TABLE IF NOT EXISTS keywords (
                id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                word VARCHAR(255) NOT NULL,
                weight INT DEFAULT 1,
                legal_procedure_id BIGINT UNSIGNED NOT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (legal_procedure_id) REFERENCES legal_procedures(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        ],
        '2025_05_08_000006_create_questions_table' => [
            "CREATE TABLE IF NOT EXISTS questions (
                id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                text TEXT NOT NULL,
                language_id BIGINT UNSIGNED NOT NULL,
                matched_response TEXT,
                confidence_score DOUBLE DEFAULT 0,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        ],
        '2025_05_08_000007_create_unanswered_questions_table' => [
            "CREATE TABLE IF NOT EXISTS unanswered_questions (
                id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                question_id BIGINT UNSIGNED,
                text TEXT NOT NULL,
                language_id BIGINT UNSIGNED NOT NULL,
                resolved BOOLEAN DEFAULT FALSE,
                resolved_by INT UNSIGNED,
                notes TEXT,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE SET NULL,
                FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        ],
        '2025_05_08_000008_create_users_and_tokens_table' => [
            "CREATE TABLE IF NOT EXISTS users (
                id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                email_verified_at TIMESTAMP NULL,
                password VARCHAR(255) NOT NULL,
                remember_token VARCHAR(100) NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS personal_access_tokens (
                id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                tokenable_type VARCHAR(255) NOT NULL,
                tokenable_id BIGINT UNSIGNED NOT NULL,
                name VARCHAR(255) NOT NULL,
                token VARCHAR(64) NOT NULL UNIQUE,
                abilities LONGTEXT,
                last_used_at TIMESTAMP NULL,
                expires_at TIMESTAMP NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                INDEX (tokenable_type, tokenable_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "INSERT INTO users (name, email, password, created_at, updated_at)
             VALUES ('Admin', 'admin@example.com', '\$2y\$12\$9DnP6.Y8P1u1Y1m.uX9oY.lZKhq3Y8P1u1Y1m.uX9oY.lZKhq3Y8P', NOW(), NOW())
             ON DUPLICATE KEY UPDATE updated_at = NOW()"
        ]
    ];
    
    // Execute pending migrations
    $batch = 1;
    $executed = 0;
    foreach ($migrations as $name => $sql_array) {
        if (in_array($name, $executedMigrations)) {
            echo "[ ] SKIP: $name (already executed)\n";
            continue;
        }
        
        try {
            foreach ((array)$sql_array as $sql) {
                if (trim($sql)) {
                    $pdo->exec($sql);
                }
            }
            
            // Record migration
            $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch, created_at) VALUES (?, ?, NOW())");
            $stmt->execute([$name, $batch]);
            
            echo "[✓] EXEC: $name\n";
            $executed++;
        } catch (Exception $e) {
            echo "[!] ERROR in $name: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n";
    if ($executed > 0) {
        echo "[✓] Migration complete! Executed $executed migration(s)\n";
    } else {
        echo "[✓] All migrations already executed\n";
    }
    
    // Verify tables
    echo "\n[*] Verifying database structure...\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = array_column($stmt->fetchAll(PDO::FETCH_NUM), 0);
    echo "[✓] Tables created: " . implode(', ', $tables) . "\n";
    
    // Show sample data
    echo "\n[*] Sample data check:\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM languages");
    echo "  - Languages: " . $stmt->fetch(PDO::FETCH_ASSOC)['count'] . "\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    echo "  - Users: " . $stmt->fetch(PDO::FETCH_ASSOC)['count'] . "\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM legal_procedures");
    echo "  - Procedures: " . $stmt->fetch(PDO::FETCH_ASSOC)['count'] . "\n";
    
    echo "\n[✓] Database ready for Laravel!\n";
    
} catch (Exception $e) {
    echo "[✗] Database Error: " . $e->getMessage() . "\n";
    exit(1);
}
