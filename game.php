<?php
// Response header
header('Content-Type: application/json; charset=utf-8');

// Storage file for persistent games
$storageDir = __DIR__ . '/data';
$storageFile = $storageDir . '/games.json';

if (!is_dir($storageDir)) {
    mkdir($storageDir, 0755, true);
}

// Read admin token from server-only file (recommended)
$adminTokenFile = $storageDir . '/admin_token.txt';
$ADMIN_TOKEN = 'admin123';
if (file_exists($adminTokenFile)) {
    $t = trim(@file_get_contents($adminTokenFile));
    if ($t !== '') $ADMIN_TOKEN = $t;
}

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

// Default games (used if storage is empty)
$defaultGames = [
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

// Helper to read storage file
function read_storage($file) {
    if (!file_exists($file)) return [];
    $txt = file_get_contents($file);
    $arr = json_decode($txt, true);
    return is_array($arr) ? $arr : [];
}

// Helper to write storage file
function write_storage($file, $data) {
    return file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
}

// Load games from storage if available, otherwise use defaults
$stored = read_storage($storageFile);
if (count($stored) > 0) {
    $games = $stored;
} else {
    $games = $defaultGames;
}

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

// Helper: validate admin token for write operations
function check_admin_token($adminToken) {
    $provided = null;
    // Check header first
    $hdr = null;
    if (!empty($_SERVER['HTTP_X_ADMIN_TOKEN'])) $hdr = $_SERVER['HTTP_X_ADMIN_TOKEN'];
    if ($hdr) $provided = $hdr;

    // If not in header, attempt to read from JSON body
    if (!$provided) {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        if (is_array($data) && isset($data['admin_token'])) $provided = $data['admin_token'];
    }

    return $provided === $adminToken;
}

// Handle POST to add a new game
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_admin_token($ADMIN_TOKEN)) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized: invalid admin token']);
        exit;
    }
    // Read JSON body
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
        exit;
    }

    // Validate required fields
    $required = ['title', 'price', 'genre', 'description', 'image'];
    foreach ($required as $f) {
        if (!isset($data[$f]) || $data[$f] === '') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Missing field: $f"]);
            exit;
        }
    }

    // Determine next ID
    $maxId = 0;
    foreach ($games as $g) {
        if (isset($g['id']) && is_numeric($g['id'])) $maxId = max($maxId, intval($g['id']));
    }

    $newGame = [
        'id' => $maxId + 1,
        'title' => $data['title'],
        'price' => floatval($data['price']),
        'genre' => $data['genre'],
        'publisher' => isset($data['publisher']) ? $data['publisher'] : '',
        'description' => $data['description'],
        'image' => $data['image']
    ];

    // Append and persist (we persist the games without generated rating/reviews)
    $persistGames = read_storage($storageFile);
    if (!is_array($persistGames) || count($persistGames) === 0) {
        // use current $games base (which may be defaults)
        $persistGames = $games;
    }

    $persistGames[] = $newGame;

    if (write_storage($storageFile, $persistGames) === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to write games file']);
        exit;
    }

    // Return the newly created game (including generated rating/reviews)
    $newGame['rating'] = generateRandomRating();
    $newGame['reviews'] = generateRandomReviews($dummyReviews, $dummyReviewers);

    echo json_encode(['success' => true, 'game' => $newGame], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    exit;
}

// Handle PUT (edit) - expects JSON body with `id` and fields to change, and admin_token
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if (!check_admin_token($ADMIN_TOKEN)) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized: invalid admin token']);
        exit;
    }

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    if (!is_array($data) || !isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing id for update']);
        exit;
    }

    $id = intval($data['id']);
    $stored = read_storage($storageFile);
    $found = false;
    foreach ($stored as &$g) {
        if (isset($g['id']) && intval($g['id']) === $id) {
            // update allowed fields
            foreach (['title','price','genre','description','image','publisher'] as $f) {
                if (isset($data[$f])) $g[$f] = $data[$f];
            }
            $found = true;
            break;
        }
    }
    unset($g);

    if (!$found) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Game not found']);
        exit;
    }

    if (write_storage($storageFile, $stored) === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to write games file']);
        exit;
    }

    echo json_encode(['success' => true, 'game' => $data]);
    exit;
}

// Handle DELETE - accepts JSON body with `id` or query param id, and admin_token
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!check_admin_token($ADMIN_TOKEN)) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized: invalid admin token']);
        exit;
    }

    $id = null;
    // try query param
    if (isset($_GET['id'])) $id = intval($_GET['id']);
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    if (!$id && is_array($data) && isset($data['id'])) $id = intval($data['id']);

    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing id for delete']);
        exit;
    }

    $stored = read_storage($storageFile);
    $new = array_values(array_filter($stored, function($g) use ($id) { return intval($g['id']) !== $id; }));

    if (count($new) === count($stored)) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Game not found']);
        exit;
    }

    if (write_storage($storageFile, $new) === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to write games file']);
        exit;
    }

    echo json_encode(['success' => true, 'deletedId' => $id]);
    exit;
}

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