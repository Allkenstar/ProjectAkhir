<?php
header('Content-Type: application/json; charset=utf-8');

$storageDir = __DIR__ . '/data';
$storageFile = $storageDir . '/library.json';

if (!is_dir($storageDir)) {
    mkdir($storageDir, 0755, true);
}

function read_storage($file) {
    if (!file_exists($file)) return [];
    $txt = file_get_contents($file);
    $arr = json_decode($txt, true);
    return is_array($arr) ? $arr : [];
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $list = read_storage($storageFile);
    echo json_encode(array_values(array_unique(array_map('intval', $list))));
    exit;
}

if ($method === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    $ids = [];
    if (is_array($data) && isset($data['ids']) && is_array($data['ids'])) {
        $ids = $data['ids'];
    } elseif (isset($_POST['ids'])) {
        $ids = is_array($_POST['ids']) ? $_POST['ids'] : [$_POST['ids']];
    }

    $ids = array_map('intval', $ids);

    // Decide behavior:
    // - If client sends { merge: true } or { action: 'add' } -> merge with existing library (checkout flow)
    // - Otherwise -> overwrite stored library with provided ids (this allows removals to persist)
    $existing = read_storage($storageFile);
    $toWrite = [];

    if ((is_array($data) && isset($data['merge']) && $data['merge']) || (is_array($data) && isset($data['action']) && $data['action'] === 'add')) {
        $toWrite = array_values(array_unique(array_merge($existing, $ids)));
    } else {
        // Default: treat provided ids as the full new library (overwrite)
        $toWrite = array_values(array_unique($ids));
    }

    if (file_put_contents($storageFile, json_encode($toWrite, JSON_PRETTY_PRINT)) === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to write storage']);
    } else {
        echo json_encode(['success' => true, 'library' => $toWrite]);
    }
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
exit;
?>