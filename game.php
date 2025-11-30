<?php
header('Content-Type: application/json; charset=utf-8');

$users = [
    [
        'id' => 1,
        'title' => "Cyberpunk 2077",
        'price' => 59.99,
        'genre' => "RPG",
        'publisher' => "Night City Studios",
        'rating' => 3.8,
        'description' => "An open-world action-adventure story set in Night City",
        'image' => "https://images.unsplash.com/photo-1542751371-adc38448a05e?w=400&h=300&fit=crop",
        'reviews' => [
            ['user' => 'PlayerOne', 'rating' => 4, 'text' => 'Great worldbuilding but buggy when it launched.'],
            ['user' => 'NightRider', 'rating' => 3, 'text' => 'Fun vibes, needs polish.']
        ]
    ],
    [
        'id' => 2,
        'title' => "The Witcher 3",
        'price' => 39.99,
        'genre' => "RPG",
        'publisher' => "CD Projekt",
        'rating' => 4.9,
        'description' => "Play as a professional monster hunter",
        'image' => "https://images.unsplash.com/photo-1538481199705-c710c4e965fc?w=400&h=300&fit=crop",
        'reviews' => [
            ['user' => 'GeraltFan', 'rating' => 5, 'text' => 'Masterpiece.'],
            ['user' => 'LoreReader', 'rating' => 5, 'text' => 'Amazing storytelling and quests.']
        ]
    ],
    [
        'id' => 3,
        'title' => "Red Dead Redemption 2",
        'price' => 49.99,
        'genre' => "Action",
        'publisher' => "Rockstar Games",
        'rating' => 4.8,
        'description' => "Experience life in the Wild West",
        'image' => "https://images.unsplash.com/photo-1511512578047-dfb367046420?w=400&h=300&fit=crop",
        'reviews' => [
            ['user' => 'Outlaw', 'rating' => 5, 'text' => 'Beautiful and immersive.'],
            ['user' => 'SaddleUp', 'rating' => 4, 'text' => 'Great story but long.']
        ]
    ],
    [
        'id' => 4,
        'title' => "Minecraft",
        'price' => 26.95,
        'genre' => "Sandbox",
        'publisher' => "Mojang",
        'rating' => 4.5,
        'description' => "Build, explore, and survive in blocky worlds",
        'image' => "https://images.unsplash.com/photo-1560253023-3ec5d502959f?w=400&h=300&fit=crop",
        'reviews' => [
            ['user' => 'BuilderBob', 'rating' => 5, 'text' => 'Endless creativity.'],
            ['user' => 'Miner99', 'rating' => 4, 'text' => 'Still addictive after years.']
        ]
    ],
    [
        'id' => 5,
        'title' => "Elden Ring",
        'price' => 59.99,
        'genre' => "Action RPG",
        'publisher' => "FromSoftware",
        'rating' => 4.7,
        'description' => "Arise, Tarnished, and explore the Lands Between",
        'image' => "https://images.unsplash.com/photo-1552820728-8b83bb6b773f?w=400&h=300&fit=crop",
        'reviews' => [
            ['user' => 'Tarnished', 'rating' => 5, 'text' => 'Challenging and rewarding.'],
            ['user' => 'LoreSeeker', 'rating' => 4, 'text' => 'Beautiful geography and bosses.']
        ]
    ],
    [
        'id' => 6,
        'title' => "GTA V",
        'price' => 29.99,
        'genre' => "Action",
        'publisher' => "Rockstar Games",
        'rating' => 4.6,
        'description' => "Experience Los Santos in this open-world crime saga",
        'image' => "https://images.unsplash.com/photo-1493711662062-fa541adb3fc8?w=400&h=300&fit=crop",
        'reviews' => [
            ['user' => 'LosSantos', 'rating' => 5, 'text' => 'Endless chaos and fun.'],
            ['user' => 'DriverX', 'rating' => 4, 'text' => 'Great open-world driving.']
        ]
    ]
];

echo json_encode($users, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
exit;
?>