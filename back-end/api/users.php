<?php
// back-end/api/users.php
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
            echo json_encode([
                'status' => 'success',
                'count' => $userRepo->getUserCount()
            ]);
            break;

        case 'list':
            $search = $_GET['search'] ?? '';
            $page = (int)($_GET['page'] ?? 1);
            if ($page < 1) $page = 1;
            $limit = 10;

            $users = $userRepo->getPaginatedUsers($search, $page, $limit);
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

        case 'create':
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) {
                throw new Exception("Invalid JSON data");
            }

            $fullname = $data['fullname'] ?? '';
            $phone = $data['phonenumber'] ?? '';
            $email = $data['email'] ?? '';

            if (!$fullname || !$phone || !$email) {
                throw new Exception("Missing required fields");
            }

            $user = sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );

            if ($userRepo->createUser($user, $fullname, $phone, $email)) {
                echo json_encode(['status' => 'success', 'message' => 'User created successfully']);
            } else {
                throw new Exception("Failed to create user");
            }
            break;

        case 'get':
            $user = $_GET['user'] ?? '';
            if (!$user) throw new Exception("User ID required");
            $userData = $userRepo->getUserById($user);
            if (!$userData) {
                http_response_code(404);
                throw new Exception("User not found");
            }
            echo json_encode(['status' => 'success', 'data' => $userData]);
            break;

        case 'update':
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) throw new Exception("Invalid JSON data");

            $user = $data['user'] ?? '';
            $fullname = $data['fullname'] ?? '';
            $phone = $data['phonenumber'] ?? '';
            $email = $data['email'] ?? '';

            if (!$user || !$fullname || !$phone || !$email) {
                throw new Exception("Missing required fields");
            }

            if ($userRepo->updateUser($user, $fullname, $phone, $email)) {
                echo json_encode(['status' => 'success', 'message' => 'User updated successfully']);
            } else {
                throw new Exception("Failed to update user");
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
