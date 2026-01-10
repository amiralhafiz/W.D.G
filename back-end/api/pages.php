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

// Assuming $pageRepo is initialized in bootstrap.php similar to $userRepo
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'count':
            echo json_encode([
                'status' => 'success',
                'count' => $pageRepo->getPageCount()
            ]);
            break;

        case 'list':
            $search = $_GET['search'] ?? '';
            $page = (int)($_GET['page'] ?? 1);
            if ($page < 1) $page = 1;
            $limit = 10;

            $pages = $pageRepo->getPaginatedPages($search, $page, $limit);
            $totalMatchingRows = $pageRepo->countSearchPages($search);
            $totalPages = ceil($totalMatchingRows / $limit);

            echo json_encode([
                'status' => 'success',
                'data' => $pages,
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $totalPages,
                    'total_records' => $totalMatchingRows
                ]
            ]);
            break;

        case 'create':
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) {
                throw new Exception("Invalid JSON data");
            }

            $status      = $data['status'] ?? 'draft';
            $title       = $data['title'] ?? '';
            $slug        = $data['slug'] ?? '';
            $description = $data['description'] ?? null;
            $content     = $data['content'] ?? null;

            if (!$title || !$slug) {
                throw new Exception("Missing required fields: title and slug are required");
            }

            if ($pageRepo->createPage($status, $title, $slug, $description, $content)) {
                echo json_encode(['status' => 'success', 'message' => 'Page created successfully']);
            } else {
                throw new Exception("Failed to create page");
            }
            break;

        case 'get':
            $id = (int)($_GET['id'] ?? 0);
            $slug = $_GET['slug'] ?? '';

            if ($id > 0) {
                $pageData = $pageRepo->getPageById($id);
            } elseif ($slug !== '') {
                $pageData = $pageRepo->getPageBySlug($slug);
            } else {
                throw new Exception("Page ID or Slug required");
            }

            if (!$pageData) {
                http_response_code(404);
                throw new Exception("Page not found");
            }
            echo json_encode(['status' => 'success', 'data' => $pageData]);
            break;

        case 'update':
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) throw new Exception("Invalid JSON data");

            $id          = (int)($data['id'] ?? 0);
            $status      = $data['status'] ?? '';
            $title       = $data['title'] ?? '';
            $slug        = $data['slug'] ?? '';
            $description = $data['description'] ?? null;
            $content     = $data['content'] ?? null;

            if (!$id || !$title || !$slug) {
                throw new Exception("Missing required fields: id, title, and slug are required");
            }

            if ($pageRepo->updatePage($id, $status, $title, $slug, $description, $content)) {
                echo json_encode(['status' => 'success', 'message' => 'Page updated successfully']);
            } else {
                throw new Exception("Failed to update page");
            }
            break;

        case 'delete':
            $id = (int)($_GET['id'] ?? 0);
            if (!$id) throw new Exception("Page ID required for deletion");

            if ($pageRepo->deletePage($id)) {
                echo json_encode(['status' => 'success', 'message' => 'Page deleted successfully']);
            } else {
                throw new Exception("Failed to delete page");
            }
            break;

        default:
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
