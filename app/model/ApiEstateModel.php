<?php

namespace GmR\model;

use GmR\model\Model;
use PDO;

class ApiEstateModel extends Model
{
    public function __construct()
    {
        $this->tableName  = 'API_ESTATE';
        $this->primaryKey = 'id_api';
    }

    // Retourne tous les api_external_id déjà importés
    public function findAllExternalIds(): array
    {
        return $this->executeQuery(
            'SELECT api_external_id FROM API_ESTATE'
        )->fetchAll(PDO::FETCH_COLUMN);
    }

    public function isImported(int $externalId): bool
    {
        return (int) $this->executeQueryWithBind(
            'SELECT COUNT(*) FROM API_ESTATE WHERE api_external_id = :eid',
            ['eid' => $externalId]
        )->fetchColumn() > 0;
    }

    public function create(int $externalId, int $estateId): void
    {
        $this->executeQueryWithBind(
            'INSERT INTO API_ESTATE (api_external_id, id_estate) VALUES (:eid, :estate)',
            ['eid' => $externalId, 'estate' => $estateId]
        );
    }
}
