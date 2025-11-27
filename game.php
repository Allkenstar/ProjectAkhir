<?php
header('Content-Type: application/json; charset=utf-8');

$users = [
    [
        'id' => 1,
        'title' => "Cyberpunk 2077",
        'price' => 59.99,
        'genre' => "RPG",
        'description' => "An open-world action-adventure story set in Night City",
        'image' => "https://images.unsplash.com/photo-1542751371-adc38448a05e?w=400&h=300&fit=crop"
    ],
    [
        'id' => 2,
        'title' => "The Witcher 3",
        'price' => 39.99,
        'genre' => "RPG",
        'description' => "Play as a professional monster hunter",
        'image' => "https://images.unsplash.com/photo-1538481199705-c710c4e965fc?w=400&h=300&fit=crop"
    ],
    [
        'id' => 3,
        'title' => "Red Dead Redemption 2",
        'price' => 49.99,
        'genre' => "Action",
        'description' => "Experience life in the Wild West",
        'image' => "https://images.unsplash.com/photo-1511512578047-dfb367046420?w=400&h=300&fit=crop"
    ],
    [
        'id' => 4,
        'title' => "Minecraft",
        'price' => 26.95,
        'genre' => "Sandbox",
        'description' => "Build, explore, and survive in blocky worlds",
        'image' => "https://images.unsplash.com/photo-1560253023-3ec5d502959f?w=400&h=300&fit=crop"
    ],
    [
        'id' => 5,
        'title' => "Elden Ring",
        'price' => 59.99,
        'genre' => "Action RPG",
        'description' => "Arise, Tarnished, and explore the Lands Between",
        'image' => "https://images.unsplash.com/photo-1552820728-8b83bb6b773f?w=400&h=300&fit=crop"
    ],
    [
        'id' => 6,
        'title' => "GTA V",
        'price' => 29.99,
        'genre' => "Action",
        'description' => "Experience Los Santos in this open-world crime saga",
        'image' => "https://images.unsplash.com/photo-1493711662062-fa541adb3fc8?w=400&h=300&fit=crop"
    ]
];

echo json_encode($users, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
exit;
?>