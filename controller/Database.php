<?php

namespace Technical_penguins\Newslurp\Controller;

class Database {
    const USER_TABLE = 'authentication';
    const STORY_TABLE = 'stories';

    public function __construct() {
        $required = ['db_name', 'db_host', 'db_user', 'db_password'];
        foreach ($required as $parameter) {
            if (!isset($_ENV[$parameter])) {
                throw new \Exception('Missing required parameter ' . $parameter .' in ENV');
            }
        }
        $dsn = 'mysql:dbname=' . $_ENV['db_name'] . ';host=' . $_ENV['db_host'];
        $this->pdo = new \PDO($dsn, $_ENV['db_user'], $_ENV['db_password']);
    }

    public static function create_table($table_name, $comments=false)
    {
        global $db;

        if ($comments) {
            $query = 'CREATE TABLE `' . $table_name . '` (
                  `vote` varchar(255) DEFAULT NULL,
                  `comments` longtext COLLATE utf8mb4_general_ci
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;';
        } else {
            $query = 'CREATE TABLE `' . $table_name . '` (
                  `vote` varchar(255) DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;';
        }
        $db->pdo->query($query);
    }

    public static function get_error() {
        global $db;

        return $db->pdo->errorInfo();
    }

    public static function query($query, $options=false) {
        global $db;
        
        try {
            if ($options) {
                $statement = $db->pdo->prepare($query);
                $statement->execute($options);
                return $statement;
            } else {
                return $db->pdo->query($query, \PDO::FETCH_OBJ);
            }
        } catch (\Exception $e) {
            var_dump($e);
        }
    }
}

$db = new Database();