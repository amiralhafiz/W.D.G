<?php
// back-end/api/health.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . '/../bootstrap.php';

try {
    $db = \App\Database::getInstance();
    echo json_encode(['status' => 'success', 'db_status' => 'Connected']);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'db_status' => 'Error', 'message' => $e.getMessage()]);
}
