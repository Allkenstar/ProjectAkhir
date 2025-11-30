<?php
header('Content-Type: application/json; charset=utf-8');

// Sample review texts for random selection
$dummyReviews = [
    "Amazing game, had a blast!",
    "Worth every penny, highly recommend.",
    "Great story and gameplay.",
    "Fun mechanics and engaging content.",
    "One of the best in its genre.",
    "Solid game with good replay value.",
    "Impressive graphics and sound design.",
    "Really enjoyed playing this.",
    "Great value for the price.",
    "Exceeded my expectations.",
    "Fun with friends, great multiplayer.",
    "Unique and memorable experience.",
    "Good challenge level.",
    "Beautiful world to explore.",
    "Kept me entertained for hours."
];

// Sample reviewer names for randomization
$dummyReviewers = [
    "GamerPro", "CasualPlayer", "ActionFan", "RPGLover", "PlaytimeGod",
    "NoobMaster", "ProGamer88", "SkillSeeker", "JoyfulGamer", "TrueGamer",
    "SolidGame", "FunTimes", "HappyPlayer", "GamingKing", "ConsoleKid"
];

$games = [
    [
        'id' => 1,
        'title' => "Cyberpunk 2077",
        'price' => 59.99,
        'genre' => "RPG",
        'publisher' => "CD Projekt Red",
        'description' => "An open-world action-adventure story set in Night City",
        'image' => "https://image.api.playstation.com/vulcan/ap/rnd/202311/2812/ae84720b553c4ce943e9c342621b60f198beda0dbf533e21.jpg"
    ],
    [
        'id' => 2,
        'title' => "The Witcher 3",
        'price' => 39.99,
        'genre' => "RPG",
        'publisher' => "CD Projekt Red",
        'description' => "Play as a professional monster hunter",
        'image' => "https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/292030/ad9240e088f953a84aee814034c50a6a92bf4516/header.jpg?t=1761131270"
    ],
    [
        'id' => 3,
        'title' => "Red Dead Redemption 2",
        'price' => 49.99,
        'genre' => "Action",
        'publisher' => "Rockstar Games",
        'description' => "Experience life in the Wild West",
        'image' => "https://image.api.playstation.com/cdn/UP1004/CUSA03041_00/Hpl5MtwQgOVF9vJqlfui6SDB5Jl4oBSq.png"
    ],
    [
        'id' => 4,
        'title' => "Minecraft",
        'price' => 26.95,
        'genre' => "Sandbox",
        'publisher' => "Mojang Studios",
        'description' => "Build, explore, and survive in blocky worlds",
        'image' => "https://www.nintendo.com/eu/media/images/10_share_images/games_15/nintendo_switch_4/2x1_NSwitch_Minecraft.jpg"
    ],
    [
        'id' => 5,
        'title' => "Elden Ring",
        'price' => 59.99,
        'genre' => "Action RPG",
        'publisher' => "FromSoftware",
        'description' => "Arise, Tarnished, and explore the Lands Between",
        'image' => "https://image.api.playstation.com/vulcan/ap/rnd/202108/0410/D8mYIXWja8knuqYlwqcqVpi1.jpg"
    ],
    [
        'id' => 6,
        'title' => "GTA V",
        'price' => 29.99,
        'genre' => "Action",
        'publisher' => "Rockstar Games",
        'description' => "Experience Los Santos in this open-world crime saga",
        'image' => "https://image.api.playstation.com/vulcan/ap/rnd/202203/0911/VIB0SeEj9vT6DTv7P4thJLZi.jpg"
    ]
    ,
    [
        'id' => 7,
        'title' => "Hollow Knight",
        'price' => 14.99,
        'genre' => "Metroidvania",
        'publisher' => "Team Cherry",
        'description' => "Explore the haunted, ruined kingdom of Hallownest in this atmospheric action-adventure.",
        'image' => "https://cdn.cloudflare.steamstatic.com/steam/apps/367520/header.jpg"
    ],
    [
        'id' => 8,
        'title' => "Stardew Valley",
        'price' => 9.99,
        'genre' => "Simulation",
        'publisher' => "ConcernedApe",
        'description' => "Build the farm of your dreams, befriend villagers, and explore caves and festivals.",
        'image' => "https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/413150/capsule_616x353.jpg?t=1754692865"
    ]
    ,
    [
        'id' => 9,
        'title' => "Warframe",
        'price' => 0.00,
        'genre' => "MMO Shooter",
        'publisher' => "Digital Extremes",
        'description' => "A cooperative free-to-play third-person online action game set in an evolving sci-fi world.",
        'image' => "https://cdn.cloudflare.steamstatic.com/steam/apps/230410/header.jpg"
    ]
];

// Function to generate random rating (3.0 to 5.0)
function generateRandomRating() {
    return round(3 + (mt_rand(0, 20) / 10), 1);
}

// Function to generate random reviews (3-5 reviews)
function generateRandomReviews($dummyReviews, $dummyReviewers) {
    $numReviews = mt_rand(3, 5);
    $reviews = [];
    for ($i = 0; $i < $numReviews; $i++) {
        $reviews[] = [
            'user' => $dummyReviewers[array_rand($dummyReviewers)],
            'rating' => mt_rand(3, 5),
            'text' => $dummyReviews[array_rand($dummyReviews)]
        ];
    }
    return $reviews;
}

// Add dynamic rating and reviews to each game
foreach ($games as &$game) {
    $game['rating'] = generateRandomRating();
    $game['reviews'] = generateRandomReviews($dummyReviews, $dummyReviewers);
}
unset($game);

// Check if a specific game is requested by title or id
if (isset($_GET['id'])) {
    $requestedId = $_GET['id'];
    // Try to find by numeric ID first
    if (is_numeric($requestedId)) {
        $gameId = intval($requestedId);
        foreach ($games as $game) {
            if ($game['id'] === $gameId) {
                echo json_encode($game, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
    }
    // Try to find by title (URL decoded)
    $searchTitle = urldecode($requestedId);
    foreach ($games as $game) {
        if (strtolower($game['title']) === strtolower($searchTitle)) {
            echo json_encode($game, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    // Not found
    http_response_code(404);
    echo json_encode(['error' => 'Game not found'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    exit;
}

// Return all games if no specific ID requested
echo json_encode($games, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
exit;
?>