<?php
namespace GmR\model;
use GmR\model\Model;

class StatusModel extends Model
{
    public function __construct()
    {
        $this->tableName = 'STATUS';
    }
    
    public function findAll(): array
    {
        return $this->executeQuery('SELECT * FROM STATUS ORDER BY label ASC')->fetchAll();
    }

    public function findById(int $id): array|false
    {
        return $this->executeQueryWithBind(
            'SELECT * FROM STATUS WHERE id_status = :id',
            ['id' => $id]
        )->fetch();
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
            ['label' => $label, 'id' => $id]
        )->rowCount() > 0;
    }

    // Échoue si des biens y sont rattachés
    public function delete(int $id): bool
    {
        return $this->executeQueryWithBind(
            'DELETE FROM STATUS WHERE id_status = :id',
            ['id' => $id]
        )->rowCount() > 0;
    }
}
