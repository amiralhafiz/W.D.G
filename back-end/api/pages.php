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

global $pageRepo;

// Ensure $pageRepo is available (it should be initialized in bootstrap.php)
if (!isset($pageRepo)) {
    throw new Exception("Page repository not initialized");
}

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
            
            // Map numeric types
            foreach ($pages as &$p) {
                $p['id'] = (int)$p['id'];
                $p['is_main'] = (bool)($p['is_main'] ?? false);
            }

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

            $status      = htmlspecialchars(strip_tags($data['status'] ?? 'draft'), ENT_QUOTES, 'UTF-8');
            $title       = htmlspecialchars(strip_tags($data['title'] ?? ''), ENT_QUOTES, 'UTF-8');
            $slug        = preg_replace('/[^a-z0-9\-]/', '', strtolower($data['slug'] ?? ''));
            $description = isset($data['description']) ? htmlspecialchars(strip_tags($data['description']), ENT_QUOTES, 'UTF-8') : null;
            $content     = $data['content'] ?? null; 
            $isMain      = (bool)($data['is_main'] ?? false);

            if (!$title || !$slug) {
                throw new Exception("Missing required fields: title and slug are required");
            }

            if ($pageRepo->createPage($status, $title, $slug, $description, $content, $isMain)) {
                echo json_encode(['status' => 'success', 'message' => 'Page created successfully']);
            } else {
                throw new Exception("Failed to create page");
            }
            break;

        case 'get_main':
            $pageData = $pageRepo->getMainPage();
            
            if (!$pageData) {
                // Check if any active pages exist at all
                $activePages = $pageRepo->getPaginatedPages('', 1, 1);
                if (empty($activePages)) {
                    http_response_code(404);
                    echo json_encode(['status' => 'error', 'error_code' => 'NULL_SLUG', 'message' => '404: NULL SLUG EXCEPTION']);
                } else {
                    http_response_code(404);
                    echo json_encode(['status' => 'error', 'error_code' => 'NO_MAIN', 'message' => '404: NO MAIN PAGE SET']);
                }
                exit;
            }
            
            $pageData['id'] = (int)$pageData['id'];
            $pageData['is_main'] = (bool)($pageData['is_main'] ?? false);
            
            echo json_encode(['status' => 'success', 'data' => $pageData]);
            break;

        case 'get':
            $id = (int)($_GET['id'] ?? 0);
            $slug = preg_replace('/[^a-z0-9\-]/', '', strtolower($_GET['slug'] ?? ''));

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
            
            if (isset($pageData['id'])) {
                $pageData['id'] = (int)$pageData['id'];
            }
            $pageData['is_main'] = (bool)($pageData['is_main'] ?? false);
            
            echo json_encode(['status' => 'success', 'data' => $pageData]);
            break;

        case 'get_by_slug':
            $slug = preg_replace('/[^a-z0-9\-]/', '', strtolower($_GET['slug'] ?? ''));

            if (!$slug) {
                throw new Exception("Slug identifier required");
            }

            $pageData = $pageRepo->getPageBySlug($slug);

            if (!$pageData) {
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'Page node not found']);
                exit;
            }

            echo json_encode(['status' => 'success', 'data' => $pageData]);
            break;

        case 'update':
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) throw new Exception("Invalid JSON data");

            $id          = (int)($data['id'] ?? 0);
            $title       = isset($data['title']) ? htmlspecialchars(strip_tags($data['title']), ENT_QUOTES, 'UTF-8') : null;
            $slug        = isset($data['slug']) ? preg_replace('/[^a-z0-9\-]/', '', strtolower($data['slug'])) : null;
            $description = isset($data['description']) ? htmlspecialchars(strip_tags($data['description']), ENT_QUOTES, 'UTF-8') : null;
            $content     = $data['content'] ?? null;
            $isMain      = (bool)($data['is_main'] ?? false);

            if (!$id) {
                throw new Exception("Missing required field: id");
            }

            // Get existing data
            $existingPage = $pageRepo->getPageById($id);
            if (!$existingPage) {
                throw new Exception("Page not found");
            }

            // Merge with existing data if fields are missing (for partial updates like 'set main')
            $title = $title ?? $existingPage['title'];
            $slug  = $slug ?? $existingPage['slug'];
            $status = isset($data['status']) ? htmlspecialchars(strip_tags($data['status']), ENT_QUOTES, 'UTF-8') : $existingPage['status'];

            if ($pageRepo->updatePage($id, $status, $title, $slug, $description, $content, $isMain)) {
                echo json_encode(['status' => 'success', 'message' => 'Page updated successfully']);
            } else {
                throw new Exception("Failed to update page");
            }
            break;

        case 'delete':
            $id = (int)($_GET['id'] ?? 0);
            if (!$id) {
                $data = json_decode(file_get_contents("php://input"), true);
                $id = (int)($data['id'] ?? 0);
            }
            if (!$id) throw new Exception("Page ID required for deletion");

            if ($pageRepo->deletePage($id)) {
                echo json_encode(['status' => 'success', 'message' => 'Page deleted successfully']);
            } else {
                throw new Exception("Failed to delete page");
            }
            break;

        case 'toggle':
            $id = (int)($_GET['id'] ?? 0);
            $status = htmlspecialchars(strip_tags($_GET['status'] ?? ''), ENT_QUOTES, 'UTF-8');
            if (!$id || !$status) throw new Exception("ID and Status required for toggle");

            if ($pageRepo->togglePageStatus($id, $status)) {
                echo json_encode(['status' => 'success', 'message' => 'Page status updated']);
            } else {
                throw new Exception("Failed to toggle page status");
            }
            break;

        case 'nav':
            $pages = $pageRepo->getActivePages(true);
            echo json_encode([
                'status' => 'success',
                'data' => $pages
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