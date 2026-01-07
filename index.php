<?php
declare(strict_types=1);

require_once "config.php";

$users = $userRepo->getAllUsers();
$userCount = $userRepo->getUserCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>W.D.G - Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .mobile-view { display: none; }
        .desktop-view { display: block; }
        @media (max-width: 768px) {
            .mobile-view { display: block; }
            .desktop-view { display: none; }
        }
        .card-user { transition: transform 0.2s; }
        .card-user:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="bi bi-people-fill me-2"></i>W.D.G</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="add.php">Add Member</a></li>
                    <li class="nav-item"><a class="nav-link" href="logs.php">Activity Logs</a></li>
                    <li class="nav-item"><a class="nav-link" href="dbcheck.php">DB Health</a></li>
                </ul>
                <span class="navbar-text">
                    <span class="badge bg-primary rounded-pill"><?= htmlspecialchars((string)$userCount) ?> Members</span>
                </span>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <!-- Desktop View: Table -->
        <div class="desktop-view shadow-sm bg-white rounded p-3">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Member Directory</h2>
                <a href="add.php" class="btn btn-primary"><i class="bi bi-person-plus-fill me-2"></i>Add New</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars((string)$user['id']) ?></td>
                            <td><strong><?= htmlspecialchars($user['name']) ?></strong></td>
                            <td><?= htmlspecialchars($user['phonenumber']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td class="text-end">
                                <a href="edit.php?id=<?= (int)$user['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil-square"></i></a>
                                <a href="delete.php?id=<?= (int)$user['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this member?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile View: Cards -->
        <div class="mobile-view">
            <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                <h3>Members</h3>
                <a href="add.php" class="btn btn-primary btn-sm"><i class="bi bi-person-plus-fill"></i></a>
            </div>
            <?php foreach ($users as $user): ?>
            <div class="card mb-3 shadow-sm card-user">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title"><?= htmlspecialchars($user['name']) ?></h5>
                        <span class="text-muted small">#<?= (int)$user['id'] ?></span>
                    </div>
                    <p class="card-text mb-1"><i class="bi bi-telephone me-2"></i><?= htmlspecialchars($user['phonenumber']) ?></p>
                    <p class="card-text mb-3"><i class="bi bi-envelope me-2"></i><?= htmlspecialchars($user['email']) ?></p>
                    <div class="d-flex gap-2">
                        <a href="edit.php?id=<?= (int)$user['id'] ?>" class="btn btn-sm btn-primary flex-fill"><i class="bi bi-pencil-square me-1"></i>Edit</a>
                        <a href="delete.php?id=<?= (int)$user['id'] ?>" class="btn btn-sm btn-outline-danger flex-fill" onclick="return confirm('Delete?')"><i class="bi bi-trash me-1"></i>Delete</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container text-center">
            <?php include_once ("footer.php"); ?>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
