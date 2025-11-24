<?php
session_start();
header('Content-Type: application/json');

// Sample user database
$users = [
    [
        'id' => 1,
        'username' => 'admin',
        'password' => 'admin',
        'role' => 'admin'
    ],
    [
        'id' => 2,
        'username' => 'user',
        'password' => 'user',
        'role' => 'user'
    ]
];

// Get POST data
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Check if fields are empty
if (empty($username) || empty($password)) {
    echo json_encode([
        'success' => false,
        'message' => 'Username and password are required'
    ]);
    exit;
}

// Validate credentials
$loginSuccess = false;
$userFound = null;

foreach ($users as $user) {
    if ($user['username'] === $username && $user['password'] === $password) {
        $loginSuccess = true;
        $userFound = $user;
        break;
    }
}

// Return JSON response
if ($loginSuccess) {
    $_SESSION['user_id'] = $userFound['id'];
    $_SESSION['username'] = $userFound['username'];
    $_SESSION['role'] = $userFound['role'];
    
    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'user' => [
            'id' => $userFound['id'],
            'username' => $userFound['username'],
            'role' => $userFound['role']
        ]
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid username or password'
    ]);
}
?>