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
     * Helper to quote identifiers if needed (e.g., if a column name is a reserved word)
     */
    private function q(string $id): string {
        // Assuming Database::quoteIdentifier is a static helper in your project
        return Database::quoteIdentifier($id);
    }

    public function getPaginatedPages(string $search, int $page = 1, int $limit = 10): array {
        $offset = ($page - 1) * $limit;
        $searchTerm = "%$search%";

        $sql = "SELECT * FROM wdg_pages
                WHERE title LIKE :s1
                OR slug LIKE :s2
                OR description LIKE :s3
                ORDER BY created_at DESC
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

    public function countSearchPages(string $search): int {
        $searchTerm = "%$search%";
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM wdg_pages WHERE title LIKE ? OR slug LIKE ? OR description LIKE ?");
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return (int) $stmt->fetchColumn();
    }

    public function getPageById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM wdg_pages WHERE id = ?");
        $stmt->execute([$id]);
        $pageData = $stmt->fetch(PDO::FETCH_ASSOC);
        return $pageData ?: null;
    }

    public function getPageBySlug(string $slug): ?array {
        $stmt = $this->db->prepare("SELECT * FROM wdg_pages WHERE slug = ?");
        $stmt->execute([$slug]);
        $pageData = $stmt->fetch(PDO::FETCH_ASSOC);
        return $pageData ?: null;
    }

    public function getMainPage(): ?array {
        $stmt = $this->db->prepare("SELECT * FROM wdg_pages WHERE is_main = TRUE AND status = 'active' LIMIT 1");
        $stmt->execute();
        $pageData = $stmt->fetch(PDO::FETCH_ASSOC);
        return $pageData ?: null;
    }

    private function resetMainPage(): void {
        $this->db->exec("UPDATE wdg_pages SET is_main = FALSE");
    }

    public function createPage(string $status, string $title, string $slug, ?string $description, ?string $content, bool $isMain = false): bool {
        if ($isMain) {
            $this->resetMainPage();
        }

        $sql = "INSERT INTO wdg_pages (status, title, slug, description, content, is_main, created_at)
                VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";

        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([$status, $title, $slug, $description, $content, $isMain ? 1 : 0]);

        if ($success) {
            $this->logger->log("Create Page", "Created page: $title (Slug: $slug)");
        }
        return $success;
    }

    public function updatePage(int $id, string $status, string $title, string $slug, ?string $description, ?string $content, bool $isMain = false): bool {
        // Check if slug is already taken by another page
        $existingPage = $this->getPageBySlug($slug);
        if ($existingPage && (int)$existingPage['id'] !== $id) {
            throw new \Exception("The slug '$slug' is already in use by another page.");
        }

        if ($isMain) {
            $this->resetMainPage();
        }

        $sql = "UPDATE wdg_pages
                SET status = ?, title = ?, slug = ?, description = ?, content = ?, is_main = ?
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([$status, $title, $slug, $description, $content, $isMain ? 1 : 0, $id]);

        if ($success) {
            $this->logger->log("Update Page", "Updated page ID: $id ($title)");
        }
        return $success;
    }

    public function deletePage(int $id): bool {
        $pageData = $this->getPageById($id);
        $stmt = $this->db->prepare("DELETE FROM wdg_pages WHERE id = ?");
        $success = $stmt->execute([$id]);

        if ($success && $pageData) {
            $this->logger->log("Delete Page", "Deleted page: " . $pageData['title'] . " (ID: $id)");
        }
        return $success;
    }

    public function togglePageStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE wdg_pages SET status = ? WHERE id = ?");
        $success = $stmt->execute([$status, $id]);

        if ($success) {
            $this->logger->log("Toggle Page Status", "Changed page ID: $id status to: $status");
        }
        return $success;
    }

    public function getPageCount(): int {
        return (int) $this->db->query("SELECT COUNT(*) FROM wdg_pages")->fetchColumn();
    }

    /**
     * Fetches pages for the navigation bar.
     * If $onlyActive is true, it only gets pages with status 'active'.
     */
    public function getActivePages(bool $onlyActive = true): array {
        $sql = "SELECT title, slug FROM wdg_pages";
        if ($onlyActive) {
            $sql .= " WHERE status = 'active'";
        }
        $sql .= " ORDER BY title ASC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
