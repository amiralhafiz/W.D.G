<?php
declare(strict_types=1);

require_once "config.php";

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phonenumber'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($id > 0 && $name && $phone && $email) {
        $userRepo->updateUser($id, $name, $phone, $email);
        header("Location: index.php");
        exit;
    }
}

$user = $userRepo->getUserById($id);
if (!$user) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>W.D.G - Edit Member</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h4>Edit Member</h4></div>
                    <div class="card-body">
                        <form method="post">
                            <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" name="phonenumber" value="<?= htmlspecialchars($user['phonenumber']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                            <button type="submit" name="update" class="btn btn-primary w-100">Update Member</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
