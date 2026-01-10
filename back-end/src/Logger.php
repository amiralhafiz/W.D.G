<?php
// back-end/src/Logger.php
declare(strict_types=1);

namespace App;

use PDO;

class Logger {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function log(string $activity, string $details = ''): bool {
        // Simple insert for new activity
        $stmt = $this->db->prepare("INSERT INTO wdg_activity_logs (activity, details) VALUES (?, ?)");
        return $stmt->execute([$activity, $details]);
    }

    /**
     * Fetch paginated and searchable logs.
     * FIXED: Unique placeholders :s1 and :s2 to prevent PDO errors.
     */
    public function getPaginatedLogs(string $search = '', int $page = 1, int $limit = 50): array {
        $offset = ($page - 1) * $limit;
        $searchTerm = "%$search%";

        $sql = "SELECT * FROM wdg_activity_logs
                WHERE activity LIKE :s1 OR details LIKE :s2
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        // Bind unique search parameters
        $stmt->bindValue(':s1', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':s2', $searchTerm, PDO::PARAM_STR);

        // Explicitly bind integers for LIMIT and OFFSET
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count logs matching the search criteria.
     */
     public function countLogs(string $search): int {
         $searchTerm = "%$search%";
         $stmt = $this->db->prepare("SELECT COUNT(*) FROM wdg_activity_logs WHERE activity LIKE ? OR details LIKE ?");
         $stmt->execute([$searchTerm, $searchTerm]);
         return (int) $stmt->fetchColumn();
     }

    public function getLogCount(): int {
        return (int) $this->db->query("SELECT COUNT(*) FROM wdg_activity_logs")->fetchColumn();
    }
}
