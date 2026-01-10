<?php
declare(strict_types=1);

/**
 * DB Health Check
 * This file verifies the connection to the back-end system.
 */

$status = "Unknown";
$message = "";

try {
    // 1. We include config, which triggers bootstrap.php
    // 2. If Database::getInstance() fails, bootstrap throws an Exception
    require_once "config.php";

    // 3. If we reach this line, the connection is successful
    $status = "Connected";
    $message = "Successfully established encrypted connection to MySQL Node.";
} catch (\Exception $e) {
    // 4. Capture the failure message thrown by bootstrap
    $status = "Error";
    $message = "Protocol Failure: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DB Health | W.D.G</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/root.css">
    <link rel="stylesheet" href="assets/css/glass-card.css">
</head>

<body class="d-flex flex-column min-vh-100 bg-dark text-white">
    <canvas id="neural-canvas"></canvas>

    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent border-bottom border-secondary border-opacity-25 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-uppercase tracking-wider" href="index.php">
                <i class="bi bi-cpu-fill me-2 text-info"></i>W.D.G
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><span class="nav-link db-status-text-nav">SYSTEM CHECKING...</span></li>
                    <li class="nav-item"><span class="nav-link"><span><i class="bi bi-clock me-1"></i> <?= date('h:i:s A') ?></span></li>
                    <li class="nav-item"><span class="nav-link"><i class="bi bi-hdd-network me-1"></i> DB ACCESS</span></li>
                    <li class="nav-item"><span class="nav-link"><span><i class="bi bi-code-slash me-1"></i> PHP <?= PHP_VERSION ?></span></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1 d-flex align-items-center pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">

                    <div class="card glass-card shadow-lg border-0">
                        <div style="height: 4px;" class="w-100 db-status-bar bg-secondary"></div>

                        <div class="card-body p-4 p-md-5 text-center">
                            <div class="mb-4">
                                <i class="bi bi-shield-check display-1 text-success db-status-icon-success d-none"></i>
                                <i class="bi bi-shield-slash display-1 text-danger db-status-icon-error d-none"></i>
                                <i class="bi bi-arrow-repeat display-1 text-info db-status-icon-loading"></i>
                            </div>

                            <h2 class="text-light fw-light mb-1">Database Health</h2>
                            <p class="text-light small text-uppercase mb-4 mono">Status Protocol</p>

                            <div class="bg-black bg-opacity-50 rounded-4 p-4 mb-4 border border-white border-opacity-10">
                                <div class="d-flex align-items-center justify-content-center mb-3">
                                    <span class="status-pulse db-status-pulse"></span>
                                    <span class="fw-bold db-status-text text-light mono">
                                        CHECKING...
                                    </span>
                                </div>
                                <p class="small text-light text-opacity-75 mb-0 mono db-status-message">
                                    Verifying secure link to database...
                                </p>
                            </div>

                            <div class="d-grid gap-3">
                                <a href="index.php" class="btn btn-success btn-lg py-3 rounded-pill shadow db-enter-btn d-none">
                                    ENTER NOW
                                </a>
                                <button onclick="window.location.reload();" class="btn btn-outline-danger btn-lg py-3 rounded-pill db-retry-btn d-none">
                                    <i class="bi bi-arrow-clockwise me-2"></i> RETRY CONNECTION
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <?php include_once ("footer.php"); ?>

    <script src="assets/js/root.js"></script>
    <script src="api-calling/health-api.js"></script>
    <script src="api-calling/dbcheck-api.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
