<?php
// front-end/pages-list.php
declare(strict_types=1);

require_once "config.php";
// CLEAN: All Legacy PHP Action logic (toggle/delete) has been removed.
// These are now handled by reading-api.js calling back-end/api/pages.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Pages | W.D.G</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/root.css">
    <link rel="stylesheet" href="assets/css/devices-view.css">
    <link rel="stylesheet" href="assets/css/table-beautify.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <canvas id="neural-canvas"></canvas>
    <?php include_once ("header.php"); ?>

    <main class="flex-grow-1 pt-3 pb-5 animate-up">
        <div class="container">
            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                <div class="alert glass-card border-0 border-start border-4 border-danger shadow-lg text-white d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-trash3-fill fs-4 me-3 text-danger"></i>
                    <div>
                        <div class="fw-bold mono text-danger">SYSTEM NOTIFICATION</div>
                        <div class="small opacity-75">Web page has been permanently purged from the registry.</div>
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-4 align-items-center">
                <div class="col-md-5">
                    <h2 class="text-light fw-light mb-0">
                        <i class="bi bi-files me-2 text-info"></i>Web Pages Management
                    </h2>
                    <p class="text-light small mono mb-0">ACTIVE REGISTRY // SESSION_ID: <span id="session-id">LOADING...</span></p>
                </div>
                <div class="col-md-7 mt-3 mt-md-0">
                    <div class="d-flex gap-2">
                        <div class="input-group border border-white border-opacity-10 rounded-3 overflow-hidden bg-black bg-opacity-25">
                            <span class="input-group-text bg-transparent border-0 text-info"><i class="bi bi-search"></i></span>
                            <input type="text" id="pageSearch" class="form-control bg-transparent border-0 text-white shadow-none text-light" placeholder="SEARCH PAGES..." onkeyup="handleKeyup(event, 'pages')">
                            <button type="button" id="resetPageSearch" class="btn btn-transparent text-warning border-0 d-none" onclick="resetFilter('pages')"><i class="bi bi-x-lg"></i></button>
                        </div>
                        <button type="button" onclick="applyPageFilter()" class="btn btn-outline-info px-4 rounded-3 fw-bold">FILTER</button>
                        <a href="add-page.php" class="btn btn-outline-primary px-4 rounded-3 fw-bold text-nowrap d-flex align-items-center">
                            <i class="bi bi-plus-circle me-2"></i> CREATE NEW
                        </a>
                    </div>
                </div>
            </div>

            <div class="desktop-view card glass-card border-0 shadow-lg overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 custom-member-table">
                            <thead class="bg-black bg-opacity-50 text-info mono small">
                                <tr>
                                    <th class="ps-4 border-0">TITLE</th>
                                    <th class="border-0">SLUG</th>
                                    <th class="border-0 text-center">STATUS</th>
                                    <th class="border-0 text-center">CREATED AT</th>
                                    <th class="pe-4 border-0 text-center">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody id="pages-table-body" class="text-light border-0"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="pages-mobile-view" class="mobile-view mt-4"></div>
            <nav id="page-pagination-container" aria-label="Page navigation" class="mt-2"></nav>
        </div>
    </main>

    <?php include_once "components/universal-delete.php"; ?>

    <?php include_once ("footer.php"); ?>

    <script src="assets/js/root.js"></script>
    <script src="api-calling/reading-api.js"></script>
    <script src="api-calling/health-api.js"></script>
</body>
</html>
