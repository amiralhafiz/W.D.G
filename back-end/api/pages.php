<?php
// back-end/api/pages.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once __DIR__ . '/../bootstrap.php';

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'count':
            // Needed for updateDashboardStats()
            echo json_encode([
                'status' => 'success',
                'count' => $pageRepo->getTotalCount()
            ]);
            break;

        case 'list':
            $search = $_GET['search'] ?? '';
            $page = (int)($_GET['page'] ?? 1);
            $limit = 10;

            $pages = $pageRepo->getPaginatedPages($search, $page, $limit);
            $totalMatchingRows = $pageRepo->countSearchPages($search);

            echo json_encode([
                'status' => 'success',
                'data' => $pages,
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => ceil($totalMatchingRows / $limit),
                    'total_records' => $totalMatchingRows
                ]
            ]);
            break;

        case 'toggle':
            $id = (int)($_GET['id'] ?? 0);
            $status = $_GET['status'] ?? '';
            if ($id > 0 && $status) {
                if ($pageRepo->updatePageStatus($id, $status)) {
                    echo json_encode(['status' => 'success']);
                    exit;
                }
            }
            throw new Exception("Status update failed");
            break;

        default:
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
