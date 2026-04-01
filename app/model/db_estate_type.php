<?php
require_once MODEL . "db_model.php";

class TypeModel extends Model
{
    protected string $tableName = 'TYPE';

    public function findAll(): array
    {
        return $this->executeQuery('SELECT * FROM TYPE ORDER BY id_type ASC')->fetchAll();
    }
}
