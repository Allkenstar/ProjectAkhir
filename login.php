<?php
$data = [
    [
        'id' => 1,
        'name' => 'admin',
        'password' => 'admin',
        'role' => 'admin'
    ],
    [
        'id' => 2,
        'name' => 'user',
        'password' => 'user',
        'role' => 'user'
    ]
    
];

echo json_encode($data);
?>

