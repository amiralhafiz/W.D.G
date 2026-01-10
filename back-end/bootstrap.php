<?php
// back-end/bootstrap.php
declare(strict_types=1);

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

$currentPage = basename($_SERVER['PHP_SELF']);

// Only attempt automatic connection if we ARE NOT on dbcheck.php or the health API
// This prevents the Fatal Error from stopping the page render
if ($currentPage !== 'dbcheck.php' && $currentPage !== 'health.php') {
    try {
        $db = Database::getInstance();
        $logger = new Logger($db);
        $userRepo = new UserRepository($db, $logger);
        $pageRepo = new PageRepository($db, $logger);
    } catch (\Exception $e) {
        header('Location: dbcheck.php');
        exit;
    }
}
