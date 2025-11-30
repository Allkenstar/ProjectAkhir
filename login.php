<?php
header('Content-Type: application/json; charset=utf-8');

// Load users from JSON file
$usersFile = __DIR__ . '/data/users.json';
$users = json_decode(file_get_contents($usersFile), true);
if (!$users) {
    $users = [];
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (!isset($_GET['userId'])) {
        echo json_encode(['success' => false, 'message' => 'Missing userId']);
        exit;
    }
    $userId = intval($_GET['userId']);
    foreach ($users as $u) {
        if ($u['id'] === $userId) {
            echo json_encode(['success' => true, 'balance' => $u['balance']]);
            exit;
        }
    }
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

if ($method === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (isset($data['userId']) && isset($data['amount'])) {
        // Balance update
        $userId = intval($data['userId']);
        $amount = floatval($data['amount']);

        foreach ($users as &$u) {
            if ($u['id'] === $userId) {
                if ($u['balance'] + $amount < 0) {
                    echo json_encode(['success' => false, 'message' => 'Insufficient balance']);
                    exit;
                }
                $u['balance'] += $amount;
                // Save updated users to file
                file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
                echo json_encode(['success' => true, 'balance' => $u['balance']]);
                exit;
            }
        }
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    } elseif (isset($_POST['username']) && isset($_POST['password'])) {
        // Login
        $username = $_POST['username'];
        $password = $_POST['password'];

        foreach ($users as $u) {
            if ($u['username'] === $username && $u['password'] === $password) {
                echo json_encode([
                    'success' => true,
                    'user' => [
                        'id' => $u['id'],
                        'username' => $u['username'],
                        'role' => $u['role'],
                        'balance' => $u['balance']
                    ]
                ]);
                exit;
            }
        }

        echo json_encode([
            'success' => false,
            'message' => 'Invalid username or password'
        ]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit;
    }
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
