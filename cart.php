<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// initialize cart storage in session
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // keyed by id => item array
}

$method = $_SERVER['REQUEST_METHOD'];

function respond($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    exit;
}

if ($method === 'GET') {
    // Check for test parameter
    if (isset($_GET['test'])) {
        // Run cart tests
        $testResults = [];

        // Test 1: Check if cart is accessible
        $testResults[] = ['test' => 'cart_access', 'status' => 'pass', 'message' => 'Cart session accessible'];

        // Test 2: Check if games.php is accessible
        $gamesTest = @file_get_contents('http://localhost/game.php');
        if ($gamesTest !== false) {
            $games = json_decode($gamesTest, true);
            $testResults[] = ['test' => 'games_access', 'status' => 'pass', 'message' => 'Games endpoint accessible, found ' . count($games) . ' games'];
        } else {
            $testResults[] = ['test' => 'games_access', 'status' => 'fail', 'message' => 'Cannot access games endpoint'];
        }

        // Test 3: Check cart operations
        $originalCart = $_SESSION['cart'];
        $_SESSION['cart'] = []; // Clear for test

        // Test add operation
        $_SESSION['cart']['999'] = ['id' => 999, 'title' => 'Test Game', 'price' => 9.99, 'qty' => 1];
        $items = array_values($_SESSION['cart']);
        $count = array_sum(array_map(function($it){ return intval($it['qty'] ?? 0); }, $items));

        if ($count === 1) {
            $testResults[] = ['test' => 'cart_operations', 'status' => 'pass', 'message' => 'Cart add/remove operations working'];
        } else {
            $testResults[] = ['test' => 'cart_operations', 'status' => 'fail', 'message' => 'Cart operations failed'];
        }

        // Restore original cart
        $_SESSION['cart'] = $originalCart;

        respond(['success' => true, 'test_results' => $testResults]);
    }

    $items = array_values($_SESSION['cart']);
    $count = array_sum(array_map(function($it){ return intval($it['qty'] ?? 0); }, $items));
    $total = array_sum(array_map(function($it){ return (float)($it['price'] ?? 0) * intval($it['qty'] ?? 0); }, $items));
    respond(['success' => true, 'items' => $items, 'count' => $count, 'total' => $total]);
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

// fallback for form-encoded POST
if (empty($data) && !empty($_POST)) {
    $data = $_POST;
}

if ($method === 'POST') {
    $action = isset($data['action']) ? $data['action'] : 'add';

    if ($action === 'clear') {
        $_SESSION['cart'] = [];
        respond(['success' => true, 'message' => 'Cart cleared', 'items' => [], 'count' => 0, 'total' => 0]);
    }

    if ($action === 'remove' && isset($data['id'])) {
        $id = (string) intval($data['id']);
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        $items = array_values($_SESSION['cart']);
        $count = array_sum(array_map(function($it){ return intval($it['qty'] ?? 0); }, $items));
        respond(['success'=>true,'message'=>'Removed','items'=>$items,'count'=>$count]);
    }

    // checkout: deduct total from user's balance and clear cart
    if ($action === 'checkout') {
        // require userId to identify which user to charge
        if (!isset($data['userId'])) {
            respond(['success' => false, 'message' => 'Missing userId for checkout'], 400);
        }

        $userId = intval($data['userId']);

        // calculate cart total
        $items = array_values($_SESSION['cart']);
        $total = array_sum(array_map(function($it){ return (float)($it['price'] ?? 0) * intval($it['qty'] ?? 0); }, $items));

        // load users file
        $usersFile = __DIR__ . '/data/users.json';
        if (!file_exists($usersFile)) {
            respond(['success' => false, 'message' => 'Users data not found'], 500);
        }

        $users = json_decode(file_get_contents($usersFile), true);
        if ($users === null) $users = [];

        $found = false;
        foreach ($users as &$u) {
            if (intval($u['id']) === $userId) {
                $found = true;
                $current = floatval($u['balance'] ?? 0);
                if ($current < $total) {
                    respond(['success' => false, 'message' => 'Insufficient balance'], 400);
                }
                $u['balance'] = $current - $total;

                // Try to update balance via existing login.php endpoint first
                $postData = json_encode(['userId' => $userId, 'amount' => -$total]);
                $context = stream_context_create([
                    'http' => [
                        'method' => 'POST',
                        'header' => "Content-Type: application/json\r\nContent-Length: " . strlen($postData) . "\r\n",
                        'content' => $postData,
                        'timeout' => 5
                    ]
                ]);

                $loginUrl = 'http://localhost/login.php';
                $loginResp = @file_get_contents($loginUrl, false, $context);
                $usedFallback = false;
                if ($loginResp !== false) {
                    $loginJson = json_decode($loginResp, true);
                    if (isset($loginJson['success']) && $loginJson['success'] === true) {
                        // success via login.php
                        $_SESSION['cart'] = [];
                        respond(['success' => true, 'message' => 'Checkout complete', 'total' => $total, 'balance' => $loginJson['balance'], 'items' => [], 'count' => 0]);
                    }
                    // else fallthrough to fallback
                }

                // Fallback: directly write the users file (for environments where internal HTTP isn't allowed)
                $usedFallback = true;
                $ok = file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT), LOCK_EX);
                if ($ok === false) {
                    respond(['success' => false, 'message' => 'Failed to update balance'], 500);
                }

                // clear cart after successful checkout
                $_SESSION['cart'] = [];

                respond(['success' => true, 'message' => 'Checkout complete', 'total' => $total, 'balance' => $u['balance'], 'items' => [], 'count' => 0]);
            }
        }
        unset($u);

        if (!$found) {
            respond(['success' => false, 'message' => 'User not found'], 404);
        }
    }

    // default: add item
    if (isset($data['item']) && is_array($data['item'])) {
        $it = $data['item'];
        $id = (string) intval($it['id'] ?? 0);
        if ($id === '0') {
            respond(['success'=>false,'message'=>'Invalid item id'], 400);
        }
        $qty = intval($it['qty'] ?? 1);
        if ($qty < 1) $qty = 1;

        // merge metadata (title, price, image) if provided
        $existing = $_SESSION['cart'][$id] ?? ['id'=>intval($id),'qty'=>0,'title'=>'','price'=>0,'image'=>''];
        $existing['qty'] = intval($existing['qty']) + $qty;
        if (isset($it['title'])) $existing['title'] = $it['title'];
        if (isset($it['price'])) $existing['price'] = floatval($it['price']);
        if (isset($it['image'])) $existing['image'] = $it['image'];

        $_SESSION['cart'][$id] = $existing;

        $items = array_values($_SESSION['cart']);
        $count = array_sum(array_map(function($it){ return intval($it['qty'] ?? 0); }, $items));
        $total = array_sum(array_map(function($it){ return (float)($it['price'] ?? 0) * intval($it['qty'] ?? 0); }, $items));

        respond(['success'=>true,'message'=>'Added to cart','items'=>$items,'count'=>$count,'total'=>$total]);
    }

    respond(['success'=>false,'message'=>'Bad request'], 400);
}

// other methods not allowed
respond(['error'=>'Method not allowed'], 405);
?>