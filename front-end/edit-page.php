<?php
// front-end/edit-page.php
declare(strict_types=1);
require_once "config.php";
global $pageRepo;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$page = null;
if ($id > 0) {
    $allPages = $pageRepo->getAllPages();
    foreach ($allPages as $p) {
        if ($p['id'] == $id) {
            $page = $p;
            break;
        }
    }
}

if (!$page) {
    header("Location: pages-list.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $slug = str_replace(' ', '-', $_POST['slug'] ?? $page['slug']);
    $description = $_POST['description'] ?? '';
    $content = $_POST['content'] ?? '';
    $status = $_POST['status'] ?? 'active';

    if ($title && $content) {
        $db = \App\Database::getInstance();
        $stmt = $db->prepare("UPDATE wdg_pages SET title = ?, slug = ?, description = ?, content = ?, status = ? WHERE id = ?");
        $stmt->execute([$title, $slug, $description, $content, $status, $id]);
        header("Location: pages-list.php?updated=1");
        exit;
    }
}
?>
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
            <div class="row">
                <div class="col-lg-4">
                    <div class="card glass-card shadow-2xl animate-up position-relative mb-4">
                        <div style="height: 6px;" class="w-100 rounded-top bg-warning shadow-sm"></div>
                        <div class="card-body p-4">
                            <h2 class="fw-light text-light mb-4 text-center">Edit Website Page</h2>

                            <?php include_once "snippet-toolbar.php"; ?>

                            <form method="post">
                                <div class="mb-3">
                                    <label class="form-label text-info small mono mb-1">PAGE TITLE</label>
                                    <input type="text" name="title" id="pageTitle" class="form-control bg-black bg-opacity-25 border-secondary text-white shadow-none" value="<?= htmlspecialchars($page['title']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-info small mono mb-1">SLUG</label>
                                    <input type="text" name="slug" class="form-control bg-black bg-opacity-25 border-secondary text-white shadow-none" value="<?= htmlspecialchars($page['slug']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-info small mono mb-1">STATUS</label>
                                    <select name="status" class="form-select bg-black bg-opacity-25 border-secondary text-white shadow-none">
                                        <option value="active" <?= $page['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= $page['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-info small mono mb-1">HTML BUFFER CONTENT</label>
                                    <textarea name="content" id="htmlBuffer" class="form-control bg-black bg-opacity-25 border-secondary text-info shadow-none mono small" rows="12" style="resize: none;" required><?= htmlspecialchars($page['content']) ?></textarea>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-warning btn-lg py-3 rounded-pill fw-bold text-uppercase">Update Page</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card glass-card shadow-2xl animate-up h-100">
                         <div style="height: 6px;" class="w-100 rounded-top bg-success shadow-sm"></div>
                         <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 py-3">
                             <h5 class="text-success mb-0 mono small"><i class="bi bi-eye-fill me-2"></i> LIVE PULSE PREVIEW</h5>
                         </div>
                         <div class="card-body p-0 overflow-auto" style="max-height: 70vh;">
                             <div id="livePreview" class="p-4 text-light"><?= $page['content'] ?></div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once ("footer.php"); ?>
    
    <script>
        const htmlBuffer = document.getElementById('htmlBuffer');
        const livePreview = document.getElementById('livePreview');

        htmlBuffer.addEventListener('input', () => {
            livePreview.innerHTML = htmlBuffer.value || '<div class="text-center opacity-50 py-5"><p>Awaiting Content Stream...</p></div>';
        });

        // Note: insertSnippet function is now loaded via snippet-toolbar.php
    </script>
</body>
</html>
