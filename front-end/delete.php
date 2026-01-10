<?php
declare(strict_types=1);

require_once "config.php";

// 1. Get the user ID as a string (default to empty string '')
$user = (string)($_GET['user'] ?? '');

// 2. Check if $user is NOT empty (instead of checking > 0)
if ($user) {
    $userRepo->deleteUser($user);
}

// 3. Redirect back to the main page
header("Location: index.php");
exit;
