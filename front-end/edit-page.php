<?php
// /front-end/edit-page.php
declare(strict_types=1);

require_once "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Page | W.D.G</title>
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
                <div class="col-md-10 col-lg-8">
                    <div class="card glass-card shadow-2xl animate-up">
                        <div style="height: 6px;" class="w-100 rounded-top bg-primary shadow-sm"></div>
                        <div class="card-body p-4 p-md-5">
                            <h2 class="fw-light text-light text-center mb-4">Edit Data Node</h2>

                            <div id="alert-container"></div>

                            <form id="edit-page-form" style="display: none;">
                                <input type="hidden" name="id" id="page-id">

                                <div class="mb-4">
                                    <label class="form-label text-primary small mono mb-1">PAGE TITLE</label>
                                    <input type="text" name="title" id="title" class="form-control bg-black bg-opacity-25 border-secondary text-white py-2" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-primary small mono mb-1">SLUG (URL PATH)</label>
                                    <input type="text" name="slug" id="slug" class="form-control bg-black bg-opacity-25 border-secondary text-info py-2" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-primary small mono mb-1">META DESCRIPTION</label>
                                    <textarea name="description" id="description" class="form-control bg-black bg-opacity-25 border-secondary text-white py-2" rows="2"></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-primary small mono mb-1">HTML CONTENT</label>
                                    <textarea name="content" id="content" class="form-control bg-black bg-opacity-25 border-secondary text-info py-2 mono small" rows="12" required></textarea>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg py-3 rounded-pill fw-bold">PUSH CHANGES</button>
                                    <a href="index.php" class="btn btn-outline-secondary rounded-pill">CANCEL</a>
                                </div>
                            </form>

                            <div id="loading-indicator" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2 text-primary mono small">RETRIEVING_DATA...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once ("footer.php"); ?>
    
    <script src="api-calling/pages-api.js"></script>
    <script src="api-calling/edit-page.js"></script>
</body>
</html>
