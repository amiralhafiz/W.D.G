<?php
// back-end/api/health.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/../bootstrap.php';

try {
    // Attempt to get the database instance
    $db = \App\Database::getInstance();

    echo json_encode([
        'status' => 'success',
        'db_status' => 'Connected',
        'message' => 'Successfully established encrypted connection to MySQL Node.'
    ]);
} catch (\Exception $e) {
    // If bootstrap or Database fails, it lands here
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'db_status' => 'Error',
        'message' => 'Protocol Failure: ' . $e->getMessage()
    ]);
}
