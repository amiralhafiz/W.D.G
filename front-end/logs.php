<?php
// front-end/logs.php
declare(strict_types=1);

require_once "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logs | W.D.G</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/root.css">
    <link rel="stylesheet" href="assets/css/table-beautify.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <canvas id="neural-canvas"></canvas>

    <?php include_once ("header.php"); ?>

    <main class="flex-grow-1 pt-3 pb-5 animate-up">
        <div class="container">

            <div class="row mb-4 align-items-center">
                <div class="col-md-6">
                    <h2 class="text-light fw-light mb-0">
                        <i class="bi bi-terminal me-2 text-info"></i>System Logs
                    </h2>
                    <p class="text-light small mono mb-0">LOG STREAM ACTIVE // SESSION ID: <span id="session-id">LOADING...</span></p>
                </div>
                <div class="col-md-6 mt-3 mt-md-0">
                    <div class="d-flex gap-2">
                        <div class="input-group border border-white border-opacity-10 rounded-3 overflow-hidden bg-black bg-opacity-25">
                            <span class="input-group-text bg-transparent border-0 text-light"><i class="bi bi-search"></i></span>
                            <input type="text"
                                  id="logSearch"
                                  class="form-control bg-transparent border-0 text-white shadow-none text-light"
                                  placeholder="SEARCH LOG..."
                                  onkeyup="handleSearchKeyup(event)"> <button type="button"
                                   id="resetSearch"
                                   class="btn btn-transparent text-warning border-0 d-none"
                                   onclick="resetFilter()">
                               <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <button type="button" onclick="applyFilter()" class="btn btn-outline-info px-4 rounded-3 fw-bold">FILTER</button>
                    </div>
                </div>
            </div>

            <div class="card glass-card border-0 shadow-lg overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 custom-log-table">
                            <thead class="bg-black bg-opacity-50 text-info mono small">
                                <tr>
                                    <th class="ps-4 border-0 text-center" style="width: 20%">TIMESTAMP</th>
                                    <th class="border-0 text-center" style="width: 15%">PROTOCOL</th>
                                    <th class="pe-4 border-0 text-center">TRACE DETAILS</th>
                                </tr>
                            </thead>
                            <tbody id="logs-table-body" class="text-light border-0">
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <div class="spinner-border text-dark" role="status"></div>
                                        <div class="mt-2 text-dark mono small">INITIALIZING SECURE STREAM...</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <nav id="pagination-container" aria-label="Page navigation" class="mt-2"></nav>

        </div>
    </main>

    <?php include_once ("footer.php"); ?>

    <script src="assets/js/root.js"></script>
    <script src="api-calling/reading-api.js"></script>
    <script src="api-calling/health-api.js"></script>
</body>
</html>
