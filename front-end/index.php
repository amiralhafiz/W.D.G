<?php
// /front-end/index.php
declare(strict_types=1);

require_once "config.php";

try {
    // This will now throw an exception instead of redirecting (due to the fix above)
    $db = \App\Database::getInstance();

    $status = "Connected";
} catch (\Exception $e) {
    $status = "Error";
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | W.D.G</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/root.css">
    <link rel="stylesheet" href="assets/css/devices-view.css">
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
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="members.php">Members <span class="badge bg-primary rounded-pill"><span id="users-count">Loading...</span> Members Found</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="logs.php">Logs <span class="badge bg-warning text-dark rounded-pill"><span id="logs-count">Loading...</span> Logs Found</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="dbcheck.php">DB Health <span class="badge bg-success rounded-pill"><?= htmlspecialchars($status) ?></span></a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1 py-4">
        <div class="container">

          <div id="carouselExampleIndicators" class="carousel slide shadow rounded overflow-hidden" data-bs-ride="carousel" data-bs-interval="3000">
              <div class="carousel-indicators">
                  <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
                  <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
                  <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
              </div>

              <div class="carousel-inner">
                  <div class="carousel-item active">
                      <img src="assets/images/welcome.png" class="d-block w-100" style="height: 60vh; object-fit: cover;" alt="Welcome">
                      <div class="carousel-caption d-none d-md-block">
                          <h2 class="display-4 fw-bold">Welcome Back</h2>
                          <p class="lead">System initialized and ready.</p>
                      </div>
                  </div>

                  <div class="carousel-item">
                      <img src="assets/images/under_construction.jpg" class="d-block w-100" style="height: 60vh; object-fit: cover;" alt="Statistics">
                      <div class="carousel-caption d-none d-md-block">
                          <h2 class="display-4 fw-bold">Under Construction</h2>
                          <p class="lead">This website is underconstruction.</p>
                      </div>
                  </div>

                  <div class="carousel-item">
                      <img src="https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=1200&q=80" class="d-block w-100" style="height: 60vh; object-fit: cover;" alt="Server">
                      <div class="carousel-caption d-none d-md-block">
                          <h2 class="display-4 fw-bold">Database Status</h2>
                          <p class="lead text-uppercase">Health Check: <?= htmlspecialchars($status) ?></p>
                      </div>
                  </div>
              </div>

              <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon"></span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                  <span class="carousel-control-next-icon"></span>
              </button>
          </div>

        </div>
    </main>

    <?php include_once ("footer.php"); ?>

    <script src="assets/js/root.js"></script>
    <script src="api-calling/reading-api.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
