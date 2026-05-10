<?php
$dbPath = __DIR__ . '/backend/database/database.sqlite';
$pdo = new PDO("sqlite:" . $dbPath);
$stmt = $pdo->query("SELECT * FROM languages");
echo "Languages: " . count($stmt->fetchAll()) . "\n";
$stmt = $pdo->query("SELECT * FROM legal_procedures");
echo "Procedures: " . count($stmt->fetchAll()) . "\n";
$stmt = $pdo->query("SELECT * FROM keywords");
echo "Keywords: " . count($stmt->fetchAll()) . "\n";
