<?php
$pdo = new PDO('sqlite:backend/database/database.sqlite');
$stmt = $pdo->query('SELECT * FROM users');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($users as $user) {
    echo "Email: " . $user['email'] . "\n";
    echo "Password Hash: " . $user['password'] . "\n";
    echo "Verify: " . (password_verify('password123', $user['password']) ? "Success" : "Failed") . "\n";
}
