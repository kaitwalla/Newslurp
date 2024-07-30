<?php

namespace Technical_penguins\Newslurp\Controller;

use Exception;
use PDO;
use PDOStatement;

class Database
{
    const string STORY_TABLE = 'stories';
    private PDO $pdo;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->pdo = new PDO('sqlite:' . __DIR__ . '/../../data.sqlite');
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
