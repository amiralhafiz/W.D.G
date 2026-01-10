<?php
// /front-end/edit-member.php
declare(strict_types=1);

require_once "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Member | W.D.G</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/root.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <canvas id="neural-canvas"></canvas>

    <?php include_once ("header.php"); ?>

    <main class="flex-grow-1 d-flex align-items-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    <div class="card glass-card shadow-2xl animate-up position-relative">
                        <a href="members.php"
                           class="position-absolute top-0 end-0 m-3 btn btn-danger d-flex align-items-center justify-content-center rounded-circle shadow-sm transition-all"
                           style="z-index: 10; width: 38px; height: 38px; padding: 0;"
                           title="Cancel Edit">
                            <i class="bi bi-x-lg" style="font-size: 1.2rem;"></i>
                        </a>
                        <div style="height: 6px;" class="w-100 rounded-top bg-warning shadow-sm"></div>

                        <div class="card-body p-4 p-md-5">
                            <div class="text-center mb-4">
                                <div class="display-6 text-warning opacity-75 mb-2">
                                    <i class="bi bi-pencil-square"></i>
                                </div>
                                <h2 class="fw-light text-light mb-1">Edit Member</h2>
                                <p class="text-light small text-uppercase mb-0 mono" style="letter-spacing: 2px;">Data Modification Protocol</p>
                            </div>

                            <div class="text-center mb-4">
                                <span class="badge bg-dark border border-warning border-opacity-25 text-warning fw-normal mono px-3 py-2">
                                    <i class="bi bi-fingerprint me-1"></i> ID: <span id="display-user-id">VERIFYING...</span>
                                </span>
                            </div>

                            <div id="alert-container"></div>

                            <form id="edit-member-form" class="mt-2" style="display: none;">
                                <input type="hidden" name="user" id="form-user-id">

                                <div class="mb-4">
                                    <label class="form-label text-warning small mono mb-1">FULLNAME</label>
                                    <div class="input-group border border-white border-opacity-10 rounded-3 overflow-hidden bg-black bg-opacity-25">
                                        <span class="input-group-text bg-transparent border-0 text-light"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control bg-transparent border-0 text-white py-2 shadow-none"
                                               name="fullname" id="fullname" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-warning small mono mb-1">CONTACT VOICE</label>
                                    <div class="input-group border border-white border-opacity-10 rounded-3 overflow-hidden bg-black bg-opacity-25">
                                        <span class="input-group-text bg-transparent border-0 text-light"><i class="bi bi-telephone"></i></span>
                                        <input type="text" class="form-control bg-transparent border-0 text-white py-2 shadow-none"
                                               name="phonenumber" id="phonenumber" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-warning small mono mb-1">EMAIL ACCESS POINT</label>
                                    <div class="input-group border border-white border-opacity-10 rounded-3 overflow-hidden bg-black bg-opacity-25">
                                        <span class="input-group-text bg-transparent border-0 text-light"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control bg-transparent border-0 text-white py-2 shadow-none"
                                               name="email" id="email" required>
                                    </div>
                                </div>

                                <div class="d-grid mt-5">
                                    <button type="submit" class="btn btn-warning btn-lg py-3 rounded-pill fw-bold shadow-sm text-uppercase tracking-wider">
                                        <i class="bi bi-save me-2"></i> Push Changes
                                    </button>
                                </div>
                            </form>

                            <div id="loading-indicator" class="text-center py-5">
                                <div class="spinner-border text-warning" role="status"></div>
                                <p class="mt-2 text-warning mono small">FETCHING_DATA...</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between text-muted px-3 mono" style="font-size: 0.7rem;">
                        <span><i class="bi bi-pencil-fill me-1"></i> MODE: OVERWRITE</span>
                        <span><i class="bi bi-shield-lock me-1"></i> AUTH: LEVEL_01</span>
                        <span><i class="bi bi-cpu me-1"></i> STACK: REST_API</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once ("footer.php"); ?>

    <script src="api-calling/members-api.js"></script>
    <script src="api-calling/edit-member.js"></script>
</body>
</html>
