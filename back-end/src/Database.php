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
            $databaseUrl = getenv('DATABASE_URL');

            if ($databaseUrl && str_starts_with($databaseUrl, 'postgres')) {
                // PostgreSQL on Replit
                $parsedUrl = parse_url($databaseUrl);
                $host = $parsedUrl['host'] ?? '';
                $port = $parsedUrl['port'] ?? 5432;
                $dbName = ltrim($parsedUrl['path'] ?? '', '/');
                $user = $parsedUrl['user'] ?? '';
                $pass = $parsedUrl['pass'] ?? '';

                $dsn = "pgsql:host=$host;port=$port;dbname=$dbName";
                self::$instance = new PDO($dsn, $user, $pass);
            } else {
                // MySQL Fallback
                $host   = defined('DB_HOST') ? DB_HOST : 'localhost';
                $port   = defined('DB_PORT') ? DB_PORT : 3306;
                $dbName = defined('DB_NAME') ? DB_NAME : 'wdg_db';
                $user   = defined('DB_USER') ? DB_USER : 'root';
                $pass   = defined('DB_PASS') ? DB_PASS : 'Aremay@91';

                try {
                    $dsn = "mysql:host=$host;port=$port;dbname=$dbName;charset=utf8mb4";
                    self::$instance = new PDO($dsn, $user, $pass);
                } catch (\PDOException $e) {
                    // If MySQL fails and we're on Replit, try to use the PostgreSQL DB anyway
                    if ($databaseUrl) {
                        $parsedUrl = parse_url($databaseUrl);
                        $host = $parsedUrl['host'] ?? '';
                        $port = $parsedUrl['port'] ?? 5432;
                        $dbName = ltrim($parsedUrl['path'] ?? '', '/');
                        $user = $parsedUrl['user'] ?? '';
                        $pass = $parsedUrl['pass'] ?? '';

                        $dsn = "pgsql:host=$host;port=$port;dbname=$dbName";
                        self::$instance = new PDO($dsn, $user, $pass);
                    } else {
                        throw $e;
                    }
                }
            }

            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::$instance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        return self::$instance;
    }

    public static function isPostgres(): bool {
        $db = self::getInstance();
        return $db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'pgsql';
    }

    public static function quoteIdentifier(string $identifier): string {
        return self::isPostgres() ? "\"$identifier\"" : "`$identifier`";
    }
}
