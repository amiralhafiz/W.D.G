<?php
declare(strict_types=1);

require_once "config.php";

$status = "Unknown";
$message = "";
$icon = "assets/images/disconnect.png";

try {
    $db = \App\Database::getInstance();
    $status = "Connected";
    $message = "Successfully connected to PostgreSQL via PDO.";
    $icon = "assets/images/connect.png";
} catch (\Exception $e) {
    $status = "Error";
    $message = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Database Health</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center text-center">
            <div class="col-md-6">
                <img src="<?= htmlspecialchars($icon) ?>" class="img-fluid mb-4" alt="Status Icon">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title <?= $status === 'Connected' ? 'text-success' : 'text-danger' ?>">
                            <?= htmlspecialchars($status) ?>
                        </h4>
                        <p class="card-text"><?= htmlspecialchars($message) ?></p>
                        <a href="index.php" class="btn btn-primary">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
