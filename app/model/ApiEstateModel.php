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

    /** Return all api_external_id values already imported */
    public function findAllExternalIds(): array
    {
        return $this->executeQuery(
            'SELECT api_external_id FROM API_ESTATE'
        )->fetchAll(PDO::FETCH_COLUMN);
    }

    /** Check whether an external API estate ID has already been imported */
    public function isImported(int $externalId): bool
    {
        return (int) $this->executeQueryWithBind(
            'SELECT COUNT(*) FROM API_ESTATE WHERE api_external_id = :eid',
            ['eid' => $externalId]
        )->fetchColumn() > 0;
    }

    /** Record the link between an external API ID and a local estate ID */
    public function create(int $externalId, int $estateId): void
    {
        $this->executeQueryWithBind(
            'INSERT INTO API_ESTATE (api_external_id, id_estate) VALUES (:eid, :estate)',
            ['eid' => $externalId, 'estate' => $estateId]
        );
    }
}
