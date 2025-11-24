<?php
header("Content-Type: application/json");

$users = [
    [
        'id' => 1,
        'username' => 'admin',
        'password' => 'admin',
        'role' => 'admin',
    ],
    [
        'id' => 2,
        'username' => 'user',
        'password' => 'user',
        'role' => 'user',
    ]
];

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing username or password'
    ]);
    exit;
}

$username = $_POST['username'];
$password = $_POST['password'];

foreach ($users as $u) {
    if ($u['username'] === $username && $u['password'] === $password) {
        echo json_encode([
            'success' => true,
            'user' => [
                'id' => $u['id'],
                'username' => $u['username'],
                'role' => $u['role']
            ]
        ]);
        exit;
    }
}

echo json_encode([
    'success' => false,
    'message' => 'Invalid username or password'
]);
