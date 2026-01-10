<?php
// back-end/src/UserRepository.php
declare(strict_types=1);

namespace App;

use PDO;

class UserRepository {
    private PDO $db;
    private Logger $logger;

    public function __construct(PDO $db, Logger $logger) {
        $this->db = $db;
        $this->logger = $logger;
    }

    private function q(string $id): string {
        return Database::quoteIdentifier($id);
    }

    public function getPaginatedUsers(string $search, int $page = 1, int $limit = 10): array {
        $offset = ($page - 1) * $limit;
        $searchTerm = "%$search%";

        $sql = "SELECT * FROM wdg_users
                WHERE fullname LIKE :s1
                OR email LIKE :s2
                OR phonenumber LIKE :s3
                ORDER BY date DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':s1', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':s2', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':s3', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countSearchUsers(string $search): int {
        $searchTerm = "%$search%";
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM wdg_users WHERE fullname LIKE ? OR email LIKE ? OR phonenumber LIKE ?");
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return (int) $stmt->fetchColumn();
    }

    public function getUserById(string $user): ?array {
        $u = $this->q('user');
        $stmt = $this->db->prepare("SELECT * FROM wdg_users WHERE $u = ?");
        $stmt->execute([$user]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        return $userData ?: null;
    }

    public function createUser(string $user, string $fullname, string $phonenumber, string $email): bool {
        $u = $this->q('user');
        $stmt = $this->db->prepare("INSERT INTO wdg_users ($u, fullname, phonenumber, email, date) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)");
        $success = $stmt->execute([$user, $fullname, $phonenumber, $email]);

        if ($success) {
            $this->logger->log("Create User", "Created user: $fullname ($email)");
        }
        return $success;
    }

    public function updateUser(string $user, string $fullname, string $phonenumber, string $email): bool {
        $u = $this->q('user');
        $stmt = $this->db->prepare("UPDATE wdg_users SET fullname = ?, phonenumber = ?, email = ? WHERE $u = ?");
        $success = $stmt->execute([$fullname, $phonenumber, $email, $user]);

        if ($success) {
            $this->logger->log("Update User", "Updated user ID: $user to $fullname ($email)");
        }
        return $success;
    }

    public function deleteUser(string $user): bool {
        $u = $this->q('user');
        $userData = $this->getUserById($user);
        $stmt = $this->db->prepare("DELETE FROM wdg_users WHERE $u = ?");
        $success = $stmt->execute([$user]);

        if ($success && $userData) {
            $this->logger->log("Delete User", "Deleted user: " . $userData['fullname'] . " (ID: $user)");
        }
        return $success;
    }

    public function toggleUserStatus(string $user, string $status): bool {
        $u = $this->q('user');
        $stmt = $this->db->prepare("UPDATE wdg_users SET status = ? WHERE $u = ?");
        $success = $stmt->execute([$status, $user]);

        if ($success) {
            $this->logger->log("Toggle User Status", "Changed user ID: $user status to: $status");
        }
        return $success;
    }

    public function getUserCount(): int {
        return (int) $this->db->query("SELECT COUNT(*) FROM wdg_users")->fetchColumn();
    }
}
