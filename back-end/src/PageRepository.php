<?php
// back-end/src/PageRepository.php
declare(strict_types=1);

namespace App;

use PDO;

class PageRepository {
    private PDO $db;
    private Logger $logger;

    public function __construct(PDO $db, Logger $logger) {
        $this->db = $db;
        $this->logger = $logger;
    }

    /**
     * Used by header.php to build the navigation menu
     */
    public function getAllPages(bool $onlyActive = false): array {
        $sql = "SELECT * FROM wdg_pages";
        if ($onlyActive) {
            $sql .= " WHERE status = 'active'";
        }
        $sql .= " ORDER BY created_at DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Used by reading-api.js for the management table
     */
    public function getPaginatedPages(string $search, int $page, int $limit): array {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT id, status, title, slug, description, content, created_at
                FROM wdg_pages
                WHERE title LIKE :search OR slug LIKE :search
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $searchTerm = "%$search%";
        $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPageBySlug(string $slug): ?array {
        $stmt = $this->db->prepare("SELECT * FROM wdg_pages WHERE slug = ?");
        $stmt->execute([$slug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function countSearchPages(string $search): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM wdg_pages WHERE title LIKE ? OR slug LIKE ?");
        $searchTerm = "%$search%";
        $stmt->execute([$searchTerm, $searchTerm]);
        return (int)$stmt->fetchColumn();
    }

    public function getTotalCount(): int {
        return (int) $this->db->query("SELECT COUNT(*) FROM wdg_pages")->fetchColumn();
    }

    public function createPage(string $title, string $slug, string $description, string $content): bool {
        $stmt = $this->db->prepare("INSERT INTO wdg_pages (title, slug, description, content, status) VALUES (?, ?, ?, ?, 'active')");
        $success = $stmt->execute([$title, $slug, $description, $content]);
        if ($success) {
            $this->logger->log("Create Page", "Created page: $title ($slug)");
        }
        return $success;
    }

    public function updatePageStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE wdg_pages SET status = ? WHERE id = ?");
        $success = $stmt->execute([$status, $id]);
        if ($success) {
            $this->logger->log("Update Page Status", "Updated page ID $id status to: $status");
        }
        return $success;
    }

    public function deletePage(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM wdg_pages WHERE id = ?");
        $success = $stmt->execute([$id]);
        if ($success) {
            $this->logger->log("Delete Page", "Deleted page ID $id");
        }
        return $success;
    }
}
