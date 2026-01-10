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
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Pages
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark bg-black bg-opacity-75 border-secondary" aria-labelledby="pagesDropdown">
                        <li><a class="dropdown-item" href="pages-list.php"><i class="bi bi-list-ul me-2"></i> Manage Pages</a></li>
                        <li><a class="dropdown-item" href="add-page.php"><i class="bi bi-plus-circle me-2"></i> Create New</a></li>
                        <li><hr class="dropdown-divider border-secondary"></li>
                        <?php foreach ($activePages as $p): ?>
                            <li><a class="dropdown-item" href="view-page.php?slug=<?= htmlspecialchars($p['slug']) ?>"><?= htmlspecialchars($p['title']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
            <div class="navbar-text ms-auto">
                <span class="badge bg-success rounded-pill db-status-badge">DB: <?= htmlspecialchars($status) ?></span>
            </div>
        </div>
    </div>
</nav>
