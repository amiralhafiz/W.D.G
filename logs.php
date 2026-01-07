<?php
declare(strict_types=1);

require_once "config.php";

$logs = $logger->getLogs(100);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>W.D.G - Activity Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">W.D.G</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="logs.php">Activity Logs</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="shadow-sm bg-white rounded p-4">
            <h2 class="mb-4">System Activity Logs</h2>
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Time</th>
                            <th>Activity</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="text-nowrap small text-muted">
                                <?= date('Y-m-d H:i:s', strtotime($log['created_at'])) ?>
                            </td>
                            <td>
                                <span class="badge <?= strpos($log['activity'], 'Delete') !== false ? 'bg-danger' : (strpos($log['activity'], 'Create') !== false ? 'bg-success' : 'bg-info') ?>">
                                    <?= htmlspecialchars($log['activity']) ?>
                                </span>
                            </td>
                            <td class="small"><?= htmlspecialchars($log['details']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
