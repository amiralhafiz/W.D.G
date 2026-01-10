<?php
// /front-end/index.php
declare(strict_types=1);

require_once "config.php";
?>
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

    <?php include_once ("header.php"); ?>

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
                          <p class="lead text-uppercase">Health Check: <span class="db-status-text">Loading...</span></p>
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
</body>
</html>
