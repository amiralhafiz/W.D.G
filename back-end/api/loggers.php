<?php
// back-end/api/loggers.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . '/../bootstrap.php';

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'count':
            echo json_encode([
                'status' => 'success',
                'count' => $logger->getLogCount()
            ]);
            break;

        case 'list':
            $search = $_GET['search'] ?? '';
            $page = (int)($_GET['page'] ?? 1);
            $limit = 10;

            // Use the logger repository for logs, not userRepo
            $logs = $logger->getPaginatedLogs($search, $page, $limit);
            $totalLogs = $logger->countLogs($search); // Ensure this method exists in Logger.php

            echo json_encode([
                'status' => 'success',
                'data' => $logs,
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => ceil($totalLogs / $limit),
                    'total_count' => $totalLogs
                ]
            ]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
