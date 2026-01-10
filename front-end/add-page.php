<?php
// front-end/add-page.php
declare(strict_types=1);
require_once "config.php";
global $pageRepo;

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
    <title>Add Page | W.D.G</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/root.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <canvas id="neural-canvas"></canvas>

    <?php include_once ("header.php"); ?>

    <main class="flex-grow-1 d-flex align-items-center py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card glass-card shadow-2xl animate-up position-relative mb-4">
                        <div style="height: 6px;" class="w-100 rounded-top bg-info shadow-sm"></div>
                        <div class="card-body p-4 p-md-5">
                            <div class="text-center mb-4">
                                <h2 class="fw-light text-light mb-1">Create Website Page</h2>
                            </div>

                            <?php include_once "snippet-toolbar.php"; ?>

                            <form method="post" class="mt-4">
                                <div class="mb-4">
                                    <label class="form-label text-info small mono mb-1">PAGE TITLE</label>
                                    <input type="text" name="title" id="pageTitle" class="form-control bg-black bg-opacity-25 border-secondary text-white py-2 shadow-none" placeholder="e.g. Dashboard" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-info small mono mb-1">META DESCRIPTION</label>
                                    <textarea name="description" class="form-control bg-black bg-opacity-25 border-secondary text-white py-2 shadow-none" rows="2" placeholder="SEO search parameters..."></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-info small mono mb-1">HTML BUFFER CONTENT</label>
                                    <textarea name="content" id="htmlBuffer" class="form-control bg-black bg-opacity-25 border-secondary text-info py-2 shadow-none mono small" rows="12" placeholder="<div class='container'>...</div>" style="resize: none;" required></textarea>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-info btn-lg py-3 rounded-pill fw-bold shadow-sm text-uppercase tracking-wider">
                                        <i class="bi bi-cloud-arrow-up me-2"></i> Publish Page
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 mb-4">
                    <div class="card glass-card shadow-2xl animate-up h-100">
                         <div style="height: 6px;" class="w-100 rounded-top bg-success shadow-sm"></div>
                         <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 py-3">
                             <h5 class="text-success mb-0 mono small"><i class="bi bi-eye-fill me-2"></i> LIVE PULSE PREVIEW</h5>
                         </div>
                         <div class="card-body p-0 overflow-auto" style="max-height: 70vh;">
                             <div id="livePreview" class="p-4 text-light">
                                 <div class="text-center opacity-50 py-5">
                                     <i class="bi bi-code-square display-1 d-block mb-3"></i>
                                     <p>Awaiting Content Stream...</p>
                                 </div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once ("footer.php"); ?>

    <script src="assets/js/root.js"></script>
    <script>
        const htmlBuffer = document.getElementById('htmlBuffer');
        const livePreview = document.getElementById('livePreview');
        const pageTitle = document.getElementById('pageTitle');

        htmlBuffer.addEventListener('input', () => {
            const content = htmlBuffer.value;
            if (content.trim()) {
                livePreview.innerHTML = content;
            } else {
                livePreview.innerHTML = '<div class="text-center opacity-50 py-5"><i class="bi bi-code-square display-1 d-block mb-3"></i><p>Awaiting Content Stream...</p></div>';
            }
        });

        // Note: insertSnippet function is now loaded via snippet-toolbar.php
    </script>
</body>
</html>
