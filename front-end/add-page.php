<?php
declare(strict_types=1);

require_once "config.php";

$totalUsers = $userRepo->getUserCount();
$totalLogs = $logger->getLogCount();

try {
    // This will now throw an exception instead of redirecting (due to the fix above)
    $db = \App\Database::getInstance();

    $status = "Connected";
} catch (\Exception $e) {
    $status = "Error";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $description = $_POST['description'] ?? '';
    $content = $_POST['content'] ?? '';

    if ($title && $content) {
        $pageRepo->createPage($title, $slug, $description, $content);
        header("Location: index.php?page_created=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Page | W.D.G</title>x
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/root.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <canvas id="neural-canvas"></canvas>

    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent border-bottom border-secondary border-opacity-25 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-uppercase tracking-wider" href="#">
                <i class="bi bi-cpu-fill me-2 text-info"></i>W.D.G
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="members.php">Members <span class="badge bg-primary rounded-pill"><?= number_format($totalUsers) ?> Members Found</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="logs.php">Logs <span class="badge bg-warning text-dark rounded-pill"><?= number_format($totalLogs) ?> Logs Found</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="dbcheck.php">DB Health <span class="badge bg-success rounded-pill"><?= htmlspecialchars($status) ?></span></a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1 d-flex align-items-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">

                    <div class="card glass-card shadow-2xl animate-up position-relative">
                        <div style="height: 6px;" class="w-100 rounded-top bg-info shadow-sm"></div>

                        <a href="pages.php"
                           class="position-absolute top-0 end-0 m-3 btn btn-outline-danger d-flex align-items-center justify-content-center rounded-circle shadow-sm transition-all"
                           style="z-index: 10; width: 38px; height: 38px; padding: 0;"
                           title="Abort Creation">
                            <i class="bi bi-x-lg"></i>
                        </a>

                        <div class="card-body p-4 p-md-5">
                            <div class="text-center mb-4">
                                <div class="display-6 text-info opacity-75 mb-2">
                                    <i class="bi bi-browser-safari"></i>
                                </div>
                                <h2 class="fw-light text-light mb-1">Create Website Page</h2>
                                <p class="text-light small text-uppercase mb-0 mono" style="letter-spacing: 2px;">Deploying New Entry</p>
                            </div>

                            <form method="post" class="mt-4">
                                <div class="mb-4">
                                    <label class="form-label text-info small mono mb-1">PAGE TITLE</label>
                                    <div class="input-group border border-white border-opacity-10 rounded-3 overflow-hidden bg-black bg-opacity-25">
                                        <span class="input-group-text bg-transparent border-0 text-light"><i class="bi bi-type-h1"></i></span>
                                        <input type="text" name="title" class="form-control bg-transparent border-0 text-white py-2 shadow-none" placeholder="e.g. Dashboard" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-info small mono mb-1">META DESCRIPTION</label>
                                    <div class="input-group border border-white border-opacity-10 rounded-3 overflow-hidden bg-black bg-opacity-25">
                                        <span class="input-group-text bg-transparent border-0 text-light"><i class="bi bi-search"></i></span>
                                        <textarea name="description" class="form-control bg-transparent border-0 text-white py-2 shadow-none" rows="2" placeholder="SEO search parameters..."></textarea>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-info small mono mb-1">HTML BUFFER CONTENT</label>
                                    <div class="border border-white border-opacity-10 rounded-3 overflow-hidden bg-black bg-opacity-25">
                                        <textarea name="content" class="form-control bg-transparent border-0 text-info py-2 shadow-none mono small" rows="10" placeholder="<div class='container'>...</div>" style="resize: none;"></textarea>
                                    </div>
                                    <div class="form-text text-light small mt-1 mono">Note: Use standard HTML5 tags.</div>
                                </div>

                                <div class="d-grid mt-5">
                                    <button type="submit" class="btn btn-info btn-lg py-3 rounded-pill fw-bold shadow-sm text-uppercase tracking-wider">
                                        <i class="bi bi-cloud-arrow-up me-2"></i> Publish Page
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between text-muted px-3 mono" style="font-size: 0.7rem;">
                        <span><i class="bi bi-code-slash me-1"></i> HTML5_READY</span>
                        <span><i class="bi bi-eye me-1"></i> PREVIEW_MODE: OFF</span>
                        <span><i class="bi bi-clock me-1"></i> TS: <?= date('Y-m-d H:i:s') ?></span>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <?php include_once ("footer.php"); ?>

    <script src="assets/js/root.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
