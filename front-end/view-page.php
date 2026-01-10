<?php
// front-end/view-page.php
declare(strict_types=1);

require_once "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>W.D.G</title>
    <meta name="description" content="Loading content...">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/root.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <canvas id="neural-canvas"></canvas>

    <?php include_once ("header.php"); ?>

    <main class="flex-grow-1 py-5">
        <div id="page-content-container" class="container animate-up">
            <div class="text-center py-5 opacity-50">
                <div class="spinner-border text-info mb-3" role="status"></div>
                <p class="mono small">SYNCHRONIZING_DATA_STREAM...</p>
            </div>
        </div>
    </main>

    <?php include_once ("footer.php"); ?>

    <script src="api-calling/pages-api.js"></script>
    <script src="api-calling/view-page.js"></script>
</body>
</html>
