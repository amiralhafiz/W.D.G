<?php
// back-end/api/users.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Required if front-end is on a different server

require_once __DIR__ . '/../bootstrap.php';

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'count':
            echo json_encode([
                'status' => 'success',
                'count' => $userRepo->getUserCount()
            ]);
            break;

        case 'list':
            $search = $_GET['search'] ?? '';
            $page = (int)($_GET['page'] ?? 1);
            if ($page < 1) $page = 1;
            $limit = 10; // Must match your Repository default or preference

            // Fetch the actual data
            $users = $userRepo->getPaginatedUsers($search, $page, $limit);

            // Fetch total count for this specific search to calculate pages
            $totalMatchingRows = $userRepo->countSearchUsers($search);
            $totalPages = ceil($totalMatchingRows / $limit);

            echo json_encode([
                'status' => 'success',
                'data' => $users,
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $totalPages,
                    'total_records' => $totalMatchingRows
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
