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

    public function getAllPages(): array {
        return $this->db->query("SELECT * FROM wdg_pages ORDER BY created_at DESC")->fetchAll();
    }

    public function getPageBySlug(string $slug): ?array {
        $stmt = $this->db->prepare("SELECT * FROM wdg_pages WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    }

    public function createPage(string $title, string $slug, string $description, string $content): bool {
        $stmt = $this->db->prepare("INSERT INTO wdg_pages (title, slug, description, content) VALUES (?, ?, ?, ?)");
        $success = $stmt->execute([$title, $slug, $description, $content]);
        if ($success) {
            $this->logger->log("Create Page", "Created page: $title ($slug)");
        }
        return $success;
    }
}
