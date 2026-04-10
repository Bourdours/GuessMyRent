<?php

namespace GmR\model;

use GmR\model\Model;

class StatusModel extends Model
{
    public function __construct()
    {
        $this->tableName = 'STATUS';
        $this->primaryKey = "id_status";
    }

    public function findByLabel(string $label): array|false
    {
        return $this->executeQueryWithBind(
            'SELECT * FROM STATUS WHERE LOWER(label) = LOWER(:label)',
            ['label' => $label]
        )->fetch();
    }

    public function create(string $label): int
    {
        $this->executeQueryWithBind(
            'INSERT INTO STATUS (label) VALUES (:label)',
            ['label' => $label]
        );
        return (int) self::connect()->lastInsertId();
    }

    public function update(int $id, string $label): bool
    {
        return $this->executeQueryWithBind(
            'UPDATE STATUS SET label = :label WHERE id_status = :id',
            [
                'label' => $label,
                'id' => $id
            ]
        )->rowCount() > 0;
    }
}
