<?php
declare(strict_types=1);
require_once "config.php";
global $pageRepo;

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
    <title><?= htmlspecialchars($page['title']) ?> | W.D.G</title>
    <meta name="description" content="<?= htmlspecialchars($page['description']) ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/root.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <canvas id="neural-canvas"></canvas>

    <?php include_once ("header.php"); ?>

    <main class="flex-grow-1 py-5">
        <div class="container animate-up">
            <?= $page['content'] ?>
        </div>
    </main>

    <?php include_once ("footer.php"); ?>
    
    <script src="assets/js/root.js"></script>
</body>
</html>
