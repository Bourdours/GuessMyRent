<?php

namespace GmR\model;

use GmR\model\Model;

class TypeModel extends Model
{
    public function __construct()
    {
        $this->tableName = 'TYPE';
        $this->primaryKey = "id_type";
    }

    /** Find a type row by label (case-insensitive); returns false if not found */
    public function findByLabel(string $label): array|false
    {
        return $this->executeQueryWithBind(
            'SELECT * FROM TYPE WHERE LOWER(label) = LOWER(:label)',
            ['label' => $label]
        )->fetch();
    }

    /** Insert a new type and return the generated ID */
    public function create(string $label): int
    {
        $this->executeQueryWithBind(
            'INSERT INTO TYPE (label) VALUES (:label)',
            ['label' => $label]
        );
        return (int) self::connect()->lastInsertId();
    }

    /** Update a type label; returns true if a row was changed */
    public function update(int $id, string $label): bool
    {
        return $this->executeQueryWithBind(
            'UPDATE TYPE SET label = :label WHERE id_type = :id',
            [
                'label' => $label,
                'id' => $id
            ]
        )->rowCount() > 0;
    }
}
