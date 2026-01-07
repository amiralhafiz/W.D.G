<?php
declare(strict_types=1);

require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Submit'])) {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phonenumber'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name && $phone && $email) {
        $userRepo->createUser($name, $phone, $email);
        header("Location: add.php?submitted=successfully");
        exit;
    }
}

$userCount = $userRepo->getUserCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>W.D.G - Add Member</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/sticky.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">W.D.G</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active" href="add.php">Add Member</a></li>
                    <li class="nav-item"><a class="nav-link" href="dbcheck.php">DB Health</a></li>
                </ul>
                <span class="navbar-text"><?= htmlspecialchars((string)$userCount) ?> member(s) total</span>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h4>Add New Member</h4></div>
                    <div class="card-body">
                        <?php if (isset($_GET['submitted'])): ?>
                            <div class="alert alert-success">Member added successfully!</div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" name="phonenumber" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <button type="submit" name="Submit" class="btn btn-primary w-100">Add Member</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer mt-auto py-3 bg-light text-center">
        <?php include_once "footer.php"; ?>
    </footer>
</body>
</html>
