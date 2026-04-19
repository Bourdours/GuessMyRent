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

    /** Find a status row by label (case-insensitive); returns false if not found */
    public function findByLabel(string $label): array|false
    {
        return $this->executeQueryWithBind(
            'SELECT * FROM STATUS WHERE LOWER(label) = LOWER(:label)',
            ['label' => $label]
        )->fetch();
    }

    /** Insert a new status and return the generated ID */
    public function create(string $label): int
    {
        $this->executeQueryWithBind(
            'INSERT INTO STATUS (label) VALUES (:label)',
            ['label' => $label]
        );
        return (int) self::connect()->lastInsertId();
    }

    /** Update a status label; returns true if a row was changed */
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
