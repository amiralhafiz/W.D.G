<?php
// back-end/bootstrap.php
declare(strict_types=1);

// Set the default timezone if necessary, e.g., for Kuala Lumpur, Malaysia
date_default_timezone_set('Asia/Kuala_Lumpur'); 

// 1. Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
});

use App\Database;
use App\Logger;
use App\UserRepository;
use App\PageRepository;

try {
    // 2. Attempt to initialize the system
    $db = Database::getInstance();

    $logger = new Logger($db);
    $userRepo = new UserRepository($db, $logger);
    $pageRepo = new PageRepository($db, $logger);

} catch (\Exception $e) {
    // 3. Handle connection failure
    $currentPage = basename($_SERVER['PHP_SELF']);

    // If we are NOT on dbcheck.php, redirect to it
    if ($currentPage !== 'dbcheck.php') {
        header('Location: dbcheck.php');
        exit;
    }

    // If we ARE already on dbcheck.php, do NOT redirect (avoid infinite loop).
    // Instead, we let the exception exist so dbcheck.php can catch and display it.
    throw $e;
}
