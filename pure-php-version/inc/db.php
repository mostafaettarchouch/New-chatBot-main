<?php
// inc/db.php
require_once __DIR__ . '/../config.php';

try {
    // 1. Connect to MySQL server (without selecting database)
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    // 3. Connect to the specific database
    $pdo->exec("USE `" . DB_NAME . "`");
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // 4. Auto-create tables if they don't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        email VARCHAR(255) UNIQUE,
        password VARCHAR(255)
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS legal_procedures (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255),
        description TEXT,
        summary TEXT
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS keywords (
        id INT AUTO_INCREMENT PRIMARY KEY,
        legal_procedure_id INT,
        word VARCHAR(255),
        weight INT,
        FOREIGN KEY (legal_procedure_id) REFERENCES legal_procedures(id) ON DELETE CASCADE
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        text TEXT,
        confidence_score FLOAT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS unanswered_questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        text TEXT,
        resolved TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    // 5. Seed default admin if table is empty
    $checkAdmin = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($checkAdmin == 0) {
        $pass = password_hash('password123', PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)")
            ->execute(['مشرف النظام', 'admin@example.com', $pass]);
    }

} catch (PDOException $e) {
    header('Content-Type: application/json');
    die(json_encode(['error' => 'Database error: ' . $e->getMessage()]));
}
