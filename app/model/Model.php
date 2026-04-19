<?php

namespace GmR\model;

use GmR\model\DbConnect;
use PDO;
use PDOStatement;
use Exception;
use RuntimeException;

abstract class Model extends DbConnect
{
    protected $tableName;

    /** Execute a SQL query */
    protected function executeQuery(string $sql): PDOStatement
    {
        try {
            $query = self::connect()->prepare($sql); // get PDO connection and compile SQL query with injection protection
            $query->execute();
            return $query;
        } catch (Exception $e) {
            throw new RuntimeException(
                "Impossible de récupérer les données : " . $e->getMessage(),
                0,
                $e
            );
        }
    }

    /** Execute a SQL query with optional parameter binding */
    protected function executeQueryWithBind(
        string $sql,
        array $params = [],
    ): PDOStatement {
        try {
            $pdo   = self::connect();
            $query = $pdo->prepare($sql);

            foreach ($params as $key => $value) {
                if ($value === null) {
                    $type = PDO::PARAM_NULL;
                } elseif (is_int($value)) {
                    $type = PDO::PARAM_INT;
                } else {
                    $type = PDO::PARAM_STR;
                }
                $query->bindValue(':' . $key, $value, $type);
            }
            $query->execute();

            return $query;
        } catch (Exception $e) {
            throw new RuntimeException(
                "Impossible de récupérer les données : " . $e->getMessage(),
                0,
                $e
            );
        }
    }

    /** Fetch all rows from a table; all columns if $attributes is empty */
    function findAll(array $attributes = []): array
    {
        $selectedAttributes = (empty($attributes) ? '*' : implode(', ', $attributes));
        $sql = "SELECT {$selectedAttributes} FROM {$this->tableName}";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }

    protected string $primaryKey;

    /** Find a single row by primary key; returns false if not found */
    public function findById(int $id): array|false
    {
        return $this->executeQueryWithBind(
            "SELECT * FROM {$this->tableName} WHERE {$this->primaryKey} = :id LIMIT 1",
            ['id' => $id]
        )->fetch();
    }

    /** Delete a row by primary key; returns true if a row was removed */
    public function deleteToVoid(int $id): bool
    {
        return $this->executeQueryWithBind(
            "DELETE FROM {$this->tableName} WHERE {$this->primaryKey} = :id",
            ['id' => $id]
        )->rowCount() > 0;
    }
}
