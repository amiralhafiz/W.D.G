<?php
declare(strict_types=1);
require_once "config.php";
global $pageRepo;

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($_GET['action'] === 'toggle') {
        $pages = $pageRepo->getAllPages();
        foreach ($pages as $p) {
            if ($p['id'] == $id) {
                $newStatus = ($p['status'] === 'active') ? 'inactive' : 'active';
                $pageRepo->updatePageStatus($id, $newStatus);
                break;
            }
        }
    } elseif ($_GET['action'] === 'delete') {
        $pageRepo->deletePage($id);
    }
    header("Location: pages-list.php");
    exit;
}

$allPages = $pageRepo->getAllPages();
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
                        <i class="bi bi-files me-2 text-info"></i>Web Pages Management
                    </h2>
                </div>
                <div class="col-md-6 text-end">
                    <a href="add-page.php" class="btn btn-outline-info rounded-pill px-4 fw-bold">
                        <i class="bi bi-plus-circle me-1"></i> CREATE NEW PAGE
                    </a>
                </div>
            </div>

            <div class="card glass-card border-0 shadow-lg overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 custom-member-table">
                            <thead class="bg-black bg-opacity-50 text-info mono small">
                                <tr>
                                    <th class="ps-4 border-0">TITLE</th>
                                    <th class="border-0">SLUG</th>
                                    <th class="border-0 text-center">STATUS</th>
                                    <th class="border-0 text-center">CREATED AT</th>
                                    <th class="pe-4 border-0 text-end">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody class="text-light border-0">
                                <?php if (empty($allPages)): ?>
                                    <tr><td colspan="5" class="text-center py-5 opacity-50">No pages found.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($allPages as $p): ?>
                                        <tr>
                                            <td class="ps-4"><strong><?= htmlspecialchars($p['title']) ?></strong></td>
                                            <td class="mono small opacity-75">/<?= htmlspecialchars($p['slug']) ?></td>
                                            <td class="text-center">
                                                <a href="pages-list.php?action=toggle&id=<?= $p['id'] ?>" class="badge rounded-pill text-decoration-none <?= $p['status'] === 'active' ? 'bg-success' : 'bg-danger' ?>">
                                                    <?= strtoupper($p['status']) ?>
                                                </a>
                                            </td>
                                            <td class="text-center mono small opacity-50"><?= htmlspecialchars($p['created_at']) ?></td>
                                            <td class="pe-4 text-end">
                                                <a href="view-page.php?slug=<?= htmlspecialchars($p['slug']) ?>" class="btn btn-sm btn-outline-light border-0" title="View"><i class="bi bi-eye"></i></a>
                                                <a href="edit-page.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-warning border-0 ms-2" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                                <a href="pages-list.php?action=delete&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger border-0 ms-2" onclick="return confirm('DESTROY PAGE: <?= htmlspecialchars($p['title']) ?>?')" title="Delete"><i class="bi bi-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once ("footer.php"); ?>
    <script src="assets/js/root.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
