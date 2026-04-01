<?php
require_once MODEL . "db_model.php";

class StatusModel extends Model
{
    protected string $tableName = 'STATUS';

    public function findAll(): array
    {
        return $this->executeQuery('SELECT * FROM STATUS ORDER BY id_status ASC')->fetchAll();
    }
}
