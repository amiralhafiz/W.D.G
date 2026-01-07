<?php
declare(strict_types=1);

namespace App;

use PDO;
use PDOException;
use RuntimeException;

class Database {
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $databaseUrl = getenv('DATABASE_URL');
            if (!$databaseUrl) {
                throw new RuntimeException("DATABASE_URL not set");
            }

            try {
                $dbUrl = parse_url($databaseUrl);
                $host = $dbUrl['host'];
                $port = $dbUrl['port'] ?? 5432;
                $dbName = ltrim($dbUrl['path'], '/');
                $user = $dbUrl['user'];
                $pass = $dbUrl['pass'];

                $dsn = "pgsql:host=$host;port=$port;dbname=$dbName;user=$user;password=$pass";
                self::$instance = new PDO($dsn);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                self::$instance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            } catch (PDOException $e) {
                throw new RuntimeException("Connection failed: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}

class Logger {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function log(string $activity, string $details = ''): bool {
        $stmt = $this->db->prepare("INSERT INTO activity_logs (activity, details) VALUES (?, ?)");
        return $stmt->execute([$activity, $details]);
    }

    public function getLogs(int $limit = 50): array {
        $stmt = $this->db->prepare("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}

class UserRepository {
    private PDO $db;
    private Logger $logger;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->logger = new Logger($db);
    }

    public function getAllUsers(): array {
        $stmt = $this->db->query("SELECT * FROM wdg_users ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getUserById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM wdg_users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function createUser(string $name, string $phonenumber, string $email): bool {
        $stmt = $this->db->prepare("INSERT INTO wdg_users (name, phonenumber, email, date) VALUES (?, ?, ?, NOW())");
        $success = $stmt->execute([$name, $phonenumber, $email]);
        if ($success) {
            $this->logger->log("Create User", "Created user: $name ($email)");
        }
        return $success;
    }

    public function updateUser(int $id, string $name, string $phonenumber, string $email): bool {
        $stmt = $this->db->prepare("UPDATE wdg_users SET name = ?, phonenumber = ?, email = ?, date = NOW() WHERE id = ?");
        $success = $stmt->execute([$name, $phonenumber, $email, $id]);
        if ($success) {
            $this->logger->log("Update User", "Updated user ID: $id to $name ($email)");
        }
        return $success;
    }

    public function deleteUser(int $id): bool {
        $user = $this->getUserById($id);
        $stmt = $this->db->prepare("DELETE FROM wdg_users WHERE id = ?");
        $success = $stmt->execute([$id]);
        if ($success && $user) {
            $this->logger->log("Delete User", "Deleted user: " . $user['name'] . " (ID: $id)");
        }
        return $success;
    }

    public function getUserCount(): int {
        return (int) $this->db->query("SELECT COUNT(*) FROM wdg_users")->fetchColumn();
    }
}

class PageRepository {
    private PDO $db;
    private Logger $logger;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->logger = new Logger($db);
    }

    public function getAllPages(): array {
        return $this->db->query("SELECT * FROM pages ORDER BY created_at DESC")->fetchAll();
    }

    public function getPageBySlug(string $slug): ?array {
        $stmt = $this->db->prepare("SELECT * FROM pages WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    }

    public function createPage(string $title, string $slug, string $description, string $content): bool {
        $stmt = $this->db->prepare("INSERT INTO pages (title, slug, description, content) VALUES (?, ?, ?, ?)");
        $success = $stmt->execute([$title, $slug, $description, $content]);
        if ($success) {
            $this->logger->log("Create Page", "Created page: $title ($slug)");
        }
        return $success;
    }
}

try {
    $db = Database::getInstance();
    $userRepo = new UserRepository($db);
    $pageRepo = new PageRepository($db);
    $logger = new Logger($db);
} catch (RuntimeException $e) {
    if (basename($_SERVER['PHP_SELF']) !== 'dbcheck.php') {
        header('Location: dbcheck.php');
        exit;
    }
}
