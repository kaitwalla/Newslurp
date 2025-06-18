<?php

namespace Technical_penguins\Newslurp\Controller;

use Exception;
use PDO;
use PDOStatement;

class Database
{
    const STORY_TABLE = 'stories';
    private PDO $pdo;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $dbType = $_ENV['DB_TYPE'] ?? 'sqlite';

        switch ($dbType) {
            case 'mysql':
                $host = $_ENV['DB_HOST'] ?? 'localhost';
                $port = $_ENV['DB_PORT'] ?? '3306';
                $database = $_ENV['DB_DATABASE'] ?? 'newslurp';
                $username = $_ENV['DB_USERNAME'] ?? 'root';
                $password = $_ENV['DB_PASSWORD'] ?? '';
                $dsn = "mysql:host={$host};port={$port};dbname={$database}";
                $this->pdo = new PDO($dsn, $username, $password);
                break;

            case 'pgsql':
                $host = $_ENV['DB_HOST'] ?? 'localhost';
                $port = $_ENV['DB_PORT'] ?? '5432';
                $database = $_ENV['DB_DATABASE'] ?? 'newslurp';
                $username = $_ENV['DB_USERNAME'] ?? 'postgres';
                $password = $_ENV['DB_PASSWORD'] ?? '';
                $dsn = "pgsql:host={$host};port={$port};dbname={$database}";
                $this->pdo = new PDO($dsn, $username, $password);
                break;

            case 'sqlite':
            default:
                $path = $_ENV['DB_PATH'] ?? __DIR__ . '/../../data.sqlite';
                $this->pdo = new PDO('sqlite:' . $path);
                break;
        }

        // Set error mode to exceptions for all connections
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function query($query, $options = false): PDOStatement|Exception
    {
        global $db;
        if (!$db) {
            $db = new Database();
        }

        try {
            if ($options) {
                $statement = $db->pdo->prepare($query);
                $statement->execute($options);
                return $statement;
            } else {
                return $db->pdo->query($query, PDO::FETCH_OBJ);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function get_error(): array
    {
        global $db;

        return $db->pdo->errorInfo();
    }
}
