<?php
declare(strict_types=1);

require_once "config.php";

try {
    $db = \App\Database::getInstance();
    $status = "Connected";
} catch (\Exception $e) {
    $status = "Error";
}

$userCount = $userRepo->getUserCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Member | W.D.G</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/root.css">
</head>
<body class="d-flex flex-column min-vh-100">
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
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="members.php">Members <span class="badge bg-primary rounded-pill"><span id="users-count">Loading...</span> Members Found</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="logs.php">Logs <span class="badge bg-warning text-dark rounded-pill"><span id="logs-count">Loading...</span> Logs Found</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="dbcheck.php">DB Health <span class="badge bg-success rounded-pill"><?= htmlspecialchars($status) ?></span></a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1 d-flex align-items-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    <div class="card glass-card shadow-2xl animate-up position-relative">
                        <a href="members.php"
                           class="position-absolute top-0 end-0 m-3 btn btn-danger d-flex align-items-center justify-content-center rounded-circle shadow-sm transition-all"
                           style="z-index: 10; width: 38px; height: 38px; padding: 0;"
                           title="Cancel">
                            <i class="bi bi-x-lg" style="font-size: 1.2rem;"></i>
                        </a>
                        <div style="height: 6px;" class="w-100 rounded-top bg-info shadow-sm"></div>

                        <div class="card-body p-4 p-md-5">
                            <div class="text-center mb-4">
                                <div class="display-6 text-info opacity-75 mb-2">
                                    <i class="bi bi-person-plus-fill"></i>
                                </div>
                                <h2 class="fw-light text-light mb-1">Add Member</h2>
                                <p class="text-light small text-uppercase mb-0 mono" style="letter-spacing: 2px;">User Registration</p>
                            </div>

                            <div id="alert-container"></div>

                            <form id="add-member-form" class="mt-4">
                                <div class="mb-4">
                                    <label class="form-label text-info small mono mb-1">FULLNAME</label>
                                    <div class="input-group border border-white border-opacity-10 rounded-3 overflow-hidden bg-black bg-opacity-25">
                                        <span class="input-group-text bg-transparent border-0 text-light"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control bg-transparent border-0 text-white py-2 shadow-none" name="fullname" placeholder="e.g. John Doe" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-info small mono mb-1">CONTACT VOICE</label>
                                    <div class="input-group border border-white border-opacity-10 rounded-3 overflow-hidden bg-black bg-opacity-25">
                                        <span class="input-group-text bg-transparent border-0 text-light"><i class="bi bi-telephone"></i></span>
                                        <input type="text" class="form-control bg-transparent border-0 text-white py-2 shadow-none" name="phonenumber" placeholder="+00 000 0000" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-info small mono mb-1">EMAIL ACCESS POINT</label>
                                    <div class="input-group border border-white border-opacity-10 rounded-3 overflow-hidden bg-black bg-opacity-25">
                                        <span class="input-group-text bg-transparent border-0 text-light"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control bg-transparent border-0 text-light py-2 shadow-none" name="email" placeholder="user@wdg-system.com" required>
                                    </div>
                                </div>

                                <div class="d-grid mt-5">
                                    <button type="submit" class="btn btn-info btn-lg py-3 rounded-pill fw-bold shadow-sm text-uppercase tracking-wider">
                                        <i class="bi bi-plus-circle me-2"></i> Register Member
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between text-muted px-3 mono" style="font-size: 0.7rem;">
                        <span><i class="bi bi-shield-lock me-1"></i> SECURE_API</span>
                        <span><i class="bi bi-database-up me-1"></i> UUID_V4_ENABLED</span>
                        <span><i class="bi bi-hdd-stack me-1"></i> COUNT: <span id="footer-user-count"><?= number_format($userCount) ?></span></span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once ("footer.php"); ?>

    <script src="assets/js/root.js"></script>
    <script src="api-calling/reading-api.js"></script>
    <script src="api-calling/members-api.js"></script>
    <script src="api-calling/add-member.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
