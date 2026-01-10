<?php
// front-end/header.php
global $userRepo, $logger, $pageRepo;
$totalUsers = $userRepo->getUserCount();
$totalLogs = $logger->getLogCount();
try {
    $db = \App\Database::getInstance();
    $status = "Connected";
} catch (\Exception $e) {
    $status = "Error";
}
$activePages = $pageRepo->getAllPages(true);
?>
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
                <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="members.php">Members <span class="badge bg-primary rounded-pill"><span id="users-count"><?= number_format($totalUsers) ?></span></span></a></li>
                <li class="nav-item"><a class="nav-link" href="logs.php">Logs <span class="badge bg-warning text-dark rounded-pill"><span id="logs-count"><?= number_format($totalLogs) ?></span></span></a></li>
                <?php foreach ($activePages as $p): ?>
                    <li class="nav-item"><a class="nav-link" href="view-page.php?slug=<?= htmlspecialchars($p['slug']) ?>"><?= htmlspecialchars($p['title']) ?></a></li>
                <?php endforeach; ?>
                <li class="nav-item"><a class="nav-link" href="add-page.php"><i class="bi bi-plus-circle"></i> Page</a></li>
            </ul>
            <div class="navbar-text ms-auto">
                <span class="badge bg-success rounded-pill db-status-badge">DB: <?= htmlspecialchars($status) ?></span>
            </div>
        </div>
    </div>
</nav>
