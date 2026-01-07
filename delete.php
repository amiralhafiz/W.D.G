<?php
declare(strict_types=1);

require_once "config.php";

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $userRepo->deleteUser($id);
}

header("Location: index.php");
exit;
