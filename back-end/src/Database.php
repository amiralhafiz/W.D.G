<?php
// back-end/src/Database.php
declare(strict_types=1);

namespace App;

use PDO;

class Database {
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            // Using your specific test values
            $host   = defined('DB_HOST') ? DB_HOST : '';
            $port   = defined('DB_PORT') ? DB_PORT : 3306;
            $dbName = defined('DB_NAME') ? DB_NAME : '';
            $user   = defined('DB_USER') ? DB_USER : '';
            $pass   = defined('DB_PASS') ? DB_PASS : '';

            $dsn = "mysql:host=$host;port=$port;dbname=$dbName;charset=utf8mb4";

            // No try/catch here. Let the Exception bubble up to bootstrap.
            self::$instance = new PDO($dsn, $user, $pass);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::$instance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        return self::$instance;
    }
}
