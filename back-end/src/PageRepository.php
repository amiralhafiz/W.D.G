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

    public function getAllPages(bool $onlyActive = false): array {
        $sql = "SELECT * FROM wdg_pages";
        if ($onlyActive) {
            $sql .= " WHERE status = 'active'";
        }
        $sql .= " ORDER BY created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getPageBySlug(string $slug): ?array {
        $stmt = $this->db->prepare("SELECT * FROM wdg_pages WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
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
