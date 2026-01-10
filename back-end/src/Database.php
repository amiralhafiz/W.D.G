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
            // Use DATABASE_URL environment variable for PostgreSQL on Replit
            $databaseUrl = getenv('DATABASE_URL');
            
            if ($databaseUrl) {
                // Parse DATABASE_URL for PostgreSQL
                $parsedUrl = parse_url($databaseUrl);
                $host = $parsedUrl['host'] ?? '';
                $port = $parsedUrl['port'] ?? 5432;
                $dbName = ltrim($parsedUrl['path'] ?? '', '/');
                $user = $parsedUrl['user'] ?? '';
                $pass = $parsedUrl['pass'] ?? '';
                
                $dsn = "pgsql:host=$host;port=$port;dbname=$dbName";
                
                self::$instance = new PDO($dsn, $user, $pass);
            } else {
                // Fallback to defined constants for MySQL (legacy support)
                $host   = defined('DB_HOST') ? DB_HOST : '';
                $port   = defined('DB_PORT') ? DB_PORT : 3306;
                $dbName = defined('DB_NAME') ? DB_NAME : '';
                $user   = defined('DB_USER') ? DB_USER : '';
                $pass   = defined('DB_PASS') ? DB_PASS : '';

                $dsn = "mysql:host=$host;port=$port;dbname=$dbName;charset=utf8mb4";
                
                self::$instance = new PDO($dsn, $user, $pass);
            }
            
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::$instance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        return self::$instance;
    }
}
