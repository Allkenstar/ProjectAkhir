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