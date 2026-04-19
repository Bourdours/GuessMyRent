<?php

namespace GmR\model;

use GmR\model\Model;

class EstateModel extends Model
{
    function __construct()
    {
        $this->tableName = 'ESTATE';
        $this->primaryKey = "id_estate";
    }

    /** Fetch all estates joined with status and type labels, newest first */
    public function findJoinedAll(): array
    {
        return $this->executeQuery(
            'SELECT e.*, s.label AS status_label, t.label AS type_label
             FROM ESTATE e
             JOIN STATUS s ON e.id_status = s.id_status
             JOIN TYPE t   ON e.id_type   = t.id_type
             ORDER BY e.id_estate DESC'
        )->fetchAll();
    }

    /** Fetch all estates belonging to a given user, newest first */
    public function findByUser(int $userId): array
    {
        return $this->executeQueryWithBind(
            'SELECT e.*, s.label AS status_label, t.label AS type_label
             FROM ESTATE e
             JOIN STATUS s ON e.id_status = s.id_status
             JOIN TYPE   t ON e.id_type   = t.id_type
             WHERE e.id_user = :id_user
             ORDER BY e.id_estate DESC',
            ['id_user' => $userId]
        )->fetchAll();
    }

    /** Fetch all estates with status "jouable" (playable) */
    public function findActive(): array
    {
        return $this->executeQueryWithBind(
            'SELECT e.*, s.label AS status_label, t.label AS type_label
             FROM ESTATE e
             JOIN STATUS s ON e.id_status = s.id_status
             JOIN TYPE   t ON e.id_type   = t.id_type
             WHERE LOWER(s.label) = :status',
            ['status' => 'jouable']
        )->fetchAll();
    }

    /** Fetch all estates with status "déposé" (pending validation) */
    public function findInactive(): array
    {
        return $this->executeQueryWithBind(
            'SELECT e.*, s.label AS status_label, t.label AS type_label
             FROM ESTATE e
             JOIN STATUS s ON e.id_status = s.id_status
             JOIN TYPE   t ON e.id_type   = t.id_type
             WHERE LOWER(s.label) = :status',
            ['status' => 'déposé']
        )->fetchAll();
    }

    /** Fetch all estates with status "archivé" */
    public function findArchived(): array
    {
        return $this->executeQueryWithBind(
            'SELECT e.*, s.label AS status_label, t.label AS type_label
             FROM ESTATE e
             JOIN STATUS s ON e.id_status = s.id_status
             JOIN TYPE   t ON e.id_type   = t.id_type
             WHERE LOWER(s.label) = :status',
            ['status' => 'archivé']
        )->fetchAll();
    }

    /** Returns a random active estate (id_status = 1) for a game */
    public function getRandom(): array|false
    {
        $query = $this->executeQuery(
            'SELECT * FROM ESTATE WHERE id_status = 1 ORDER BY RAND() LIMIT 1'
        );
        return $query->fetch();
    }

    /** Returns a random playable estate, excluding already-played IDs */
    public function findRandomActive(array $excludeIds = []): array|false
    {
        $params  = [];
        $exclude = '';

        if (!empty($excludeIds)) {
            $placeholders = [];
            foreach ($excludeIds as $i => $id) {
                $key            = 'excl_' . $i;
                $params[$key]   = $id;
                $placeholders[] = ':' . $key;
            }
            $exclude = 'AND e.id_estate NOT IN (' . implode(',', $placeholders) . ')';
        }

        return $this->executeQueryWithBind(
            "SELECT e.*, s.label AS status_label, t.label AS type_label
             FROM ESTATE e
             JOIN STATUS s ON e.id_status = s.id_status
             JOIN TYPE   t ON e.id_type   = t.id_type
             WHERE LOWER(s.label) = 'jouable'
             $exclude
             ORDER BY RAND()
             LIMIT 1",
            $params
        )->fetch();
    }

    /** Count all playable estates */
    public function countActive(): int
    {
        return (int) $this->executeQueryWithBind(
            "SELECT COUNT(*) FROM ESTATE e
             JOIN STATUS s ON e.id_status = s.id_status
             WHERE LOWER(s.label) = :status",
            ['status' => 'jouable']
        )->fetchColumn();
    }

    /** Count distinct cities among playable estates */
    public function countCities(): int
    {
        return (int) $this->executeQueryWithBind(
            "SELECT COUNT(DISTINCT e.city) FROM ESTATE e
             JOIN STATUS s ON e.id_status = s.id_status
             WHERE LOWER(s.label) = :status",
            ['status' => 'jouable']
        )->fetchColumn();
    }

    /** Update the status of an estate */
    public function updateStatus(int $id, int $statusId): void
    {
        $this->executeQueryWithBind(
            'UPDATE ESTATE SET id_status = :status WHERE id_estate = :id',
            [
                'status' => $statusId,
                'id' => $id
            ]
        );
    }

    /** Update all editable fields of an estate; returns true if a row was changed */
    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        return $this->executeQueryWithBind(
            'UPDATE ESTATE SET
                rent                = :rent,
                is_charges_included = :is_charges_included,
                adress              = :adress,
                city                = :city,
                postcode            = :postcode,
                gps_y               = :gps_y,
                gps_x               = :gps_x,
                square_meters       = :square_meters,
                room                = :room,
                chamber             = :chamber,
                floor              = :floor,
                description         = :description,
                image1              = :image1,
                image2              = :image2,
                image3              = :image3,
                image4              = :image4,
                id_status           = :id_status,
                id_type             = :id_type
             WHERE id_estate = :id',
            $data
        )->rowCount() > 0;
    }

    /** Insert a new estate and return its generated ID */
    public function create(array $data): int
    {
        $this->executeQueryWithBind(
            'INSERT INTO ESTATE
                (rent, is_charges_included, adress, city, postcode, gps_y, gps_x, square_meters,
                 room, chamber, floor, description, image1, image2, image3, image4,
                 id_status, id_type, id_user)
             VALUES
                (:rent, :is_charges_included, :adress, :city, :postcode, :gps_y, :gps_x, :square_meters,
                 :room, :chamber, :floor, :description, :image1, :image2, :image3, :image4,
                 :id_status, :id_type, :id_user)',
            $data
        );
        return (int) self::connect()->lastInsertId();
    }

    /** Average rent across all playable estates; falls back to 800 if no data */
    public function avgRent(): int
    {
        $avg = $this->executeQueryWithBind(
            "SELECT AVG(e.rent)
             FROM ESTATE e
             JOIN STATUS s ON e.id_status = s.id_status
             WHERE LOWER(s.label) = :status",
            ['status' => 'jouable']
        )->fetchColumn();
        return $avg !== false ? (int) round($avg) : 800;
    }
}
