<?php
// api.php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
mb_internal_encoding("UTF-8");
session_start();

require_once __DIR__ . '/inc/db.php';

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Auth Middleware Helper
function checkAuth() {
    if (!isset($_SESSION['admin_logged_in'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
}

// 1. PUBLIC ACTIONS
if ($action === 'login' && $method === 'POST') {
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = $user['name'];
        echo json_encode(['success' => true, 'name' => $user['name']]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
    }
    exit;
}

if ($action === 'chat' && $method === 'POST') {
    $message = trim($input['message'] ?? '');
    $stmt = $pdo->query("SELECT lp.*, GROUP_CONCAT(k.word) as kw FROM legal_procedures lp LEFT JOIN keywords k ON k.legal_procedure_id = lp.id GROUP BY lp.id");
    $procedures = $stmt->fetchAll();
    
    $bestMatch = null;
    $maxScore = 0;
    
    foreach ($procedures as $p) {
        $score = 0;
        $kws = explode(',', $p['kw'] ?? '');
        foreach ($kws as $kw) {
            if ($kw && mb_strpos(mb_strtolower($message), mb_strtolower($kw)) !== false) {
                $score++;
            }
        }
        if ($score > $maxScore) {
            $maxScore = $score;
            $bestMatch = $p;
        }
    }
    
    if ($bestMatch && $maxScore > 0) {
        $pdo->prepare("INSERT INTO questions (text, confidence_score) VALUES (?, ?)")->execute([$message, $maxScore]);
        echo json_encode(['matched' => true, 'response' => $bestMatch['description'], 'title' => $bestMatch['title']]);
    } else {
        $pdo->prepare("INSERT INTO unanswered_questions (text) VALUES (?)")->execute([$message]);
        echo json_encode(['matched' => false, 'response' => 'عذراً، لم أجد إجابة دقيقة. سيقوم المشرف بمراجعة سؤالك قريباً.']);
    }
    exit;
}

// 2. PROTECTED ACTIONS
checkAuth();

if ($action === 'stats') {
    $stats = [
        'total_questions' => $pdo->query("SELECT COUNT(*) FROM questions")->fetchColumn(),
        'unanswered' => $pdo->query("SELECT COUNT(*) FROM unanswered_questions WHERE resolved = 0")->fetchColumn(),
        'procedures' => $pdo->query("SELECT COUNT(*) FROM legal_procedures")->fetchColumn(),
        'history' => $pdo->query("SELECT COUNT(*) as count, date(created_at) as date FROM questions GROUP BY date(created_at) LIMIT 7")->fetchAll()
    ];
    echo json_encode($stats);
    exit;
}

if ($action === 'get_procedures') {
    echo json_encode($pdo->query("SELECT * FROM legal_procedures ORDER BY id DESC")->fetchAll());
    exit;
}

if ($action === 'save_procedure') {
    $title = $input['title'] ?? '';
    $desc = $input['description'] ?? '';
    if ($input['id']) {
        $pdo->prepare("UPDATE legal_procedures SET title = ?, description = ? WHERE id = ?")->execute([$title, $desc, $input['id']]);
    } else {
        $pdo->prepare("INSERT INTO legal_procedures (title, description) VALUES (?, ?)")->execute([$title, $desc]);
    }
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'delete_procedure') {
    $pdo->prepare("DELETE FROM legal_procedures WHERE id = ?")->execute([$input['id']]);
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'unanswered') {
    echo json_encode($pdo->query("SELECT * FROM unanswered_questions WHERE resolved = 0 ORDER BY id DESC")->fetchAll());
    exit;
}

if ($action === 'resolve') {
    $pdo->prepare("UPDATE unanswered_questions SET resolved = 1 WHERE id = ?")->execute([$input['id']]);
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'logout') {
    session_destroy();
    echo json_encode(['success' => true]);
    exit;
}
