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

    // public function findAll(): array
    // {
    //     return $this->executeQuery('SELECT * FROM TYPE ORDER BY label ASC')->fetchAll();
    // }

    // public function findById(int $id): array|false
    // {
    //     return $this->executeQueryWithBind(
    //         'SELECT * FROM TYPE WHERE id_type = :id',
    //         ['id' => $id]
    //     )->fetch();
    // }

    public function findByLabel(string $label): array|false
    {
        return $this->executeQueryWithBind(
            'SELECT * FROM TYPE WHERE LOWER(label) = LOWER(:label)',
            ['label' => $label]
        )->fetch();
    }

    public function create(string $label): int
    {
        $this->executeQueryWithBind(
            'INSERT INTO TYPE (label) VALUES (:label)',
            ['label' => $label]
        );
        return (int) self::connect()->lastInsertId();
    }

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

    // // Échoue si des biens y sont rattachés
    // public function delete(int $id): bool
    // {
    //     return $this->executeQueryWithBind(
    //         'DELETE FROM TYPE WHERE id_type = :id',
    //         ['id' => $id]
    //     )->rowCount() > 0;
    // }
}
