<?php
declare(strict_types=1);
require_once "config.php";

$slug = $_GET['slug'] ?? '';
$page = $pageRepo->getPageBySlug($slug);

if (!$page) {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 Page Not Found</h1>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($page['title']) ?></title>
    <meta name="description" content="<?= htmlspecialchars($page['description']) ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <?= $page['content'] // Render raw HTML content as requested ?>
    </div>
</body>
</html>