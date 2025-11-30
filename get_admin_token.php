<?php
header('Content-Type: application/json; charset=utf-8');

$storageDir = __DIR__ . '/data';
$tokenFile = $storageDir . '/admin_token.txt';

// Very small demo credential check. In production, replace with real user store and secure auth.
// Read user store
$usersFile = __DIR__ . '/data/users.json';
$users = [];
if (file_exists($usersFile)) {
    $txt = @file_get_contents($usersFile);
    $u = json_decode($txt, true);
    if (is_array($u)) $users = $u;
}

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data) || !isset($data['username']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing credentials']);
    exit;
}


$username = $data['username'];
$password = $data['password'];

// Lookup user in users.json
$found = null;
foreach ($users as $u) {
    if (isset($u['username']) && $u['username'] === $username && isset($u['password']) && $u['password'] === $password) {
        $found = $u;
        break;
    }
}

if (!$found) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    exit;
}

if (!isset($found['role']) || $found['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden: admin role required']);
    exit;
}

if (!file_exists($tokenFile)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Token file missing on server']);
    exit;
}

$token = trim(@file_get_contents($tokenFile));
if ($token === '') {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Token not configured']);
    exit;
}

echo json_encode(['success' => true, 'token' => $token]);
exit;
?>