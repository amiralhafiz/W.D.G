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

class UserRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db = $db;
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
        return $stmt->execute([$name, $phonenumber, $email]);
    }

    public function updateUser(int $id, string $name, string $phonenumber, string $email): bool {
        $stmt = $this->db->prepare("UPDATE wdg_users SET name = ?, phonenumber = ?, email = ?, date = NOW() WHERE id = ?");
        return $stmt->execute([$name, $phonenumber, $email, $id]);
    }

    public function deleteUser(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM wdg_users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getUserCount(): int {
        return (int) $this->db->query("SELECT COUNT(*) FROM wdg_users")->fetchColumn();
    }
}

try {
    $db = Database::getInstance();
    $userRepo = new UserRepository($db);
} catch (RuntimeException $e) {
    if (basename($_SERVER['PHP_SELF']) !== 'dbcheck.php') {
        header('Location: dbcheck.php');
        exit;
    }
}
