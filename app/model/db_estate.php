<?php
require_once MODEL . "db_model.php";

class EstateModel extends Model
{
    protected string $tableName = 'ESTATE';

    /** Retourne un bien actif (id_status = 1) aléatoire pour une partie */
    public function getRandom(): array|false
    {
        $query = $this->executeQuery(
            'SELECT * FROM ESTATE WHERE id_status = 1 ORDER BY RAND() LIMIT 1'
        );
        return $query->fetch();
    }

    public function findById(int $id): array|false
    {
        $query = $this->executeQueryWithBind(
            'SELECT * FROM ESTATE WHERE id_estate = :id LIMIT 1',
            ['id' => $id]
        );
        return $query->fetch();
    }

    public function findAll(): array
    {
        return $this->executeQuery(
            'SELECT e.*, s.label AS status_label, t.label AS type_label
             FROM ESTATE e
             JOIN STATUS s ON e.id_status = s.id_status
             JOIN TYPE t   ON e.id_type   = t.id_type
             ORDER BY e.id_estate DESC'
        )->fetchAll();
    }

    public function updateStatus(int $id, int $statusId): void
    {
        $this->executeQueryWithBind(
            'UPDATE ESTATE SET id_status = :status WHERE id_estate = :id',
            ['status' => $statusId, 'id' => $id]
        );
    }

    public function create(array $data): int
    {
        $this->executeQueryWithBind(
            'INSERT INTO ESTATE
                (rent, is_charges_included, adress, city, postcode, gps_y, gps_x, square_meters,
                 room, chamber, floor_, description, image1, image2, image3, image4,
                 id_status, id_type, id_user)
             VALUES
                (:rent, :is_charges_included, :adress, :city, :postcode, :gps_y, :gps_x, :square_meters,
                 :room, :chamber, :floor_, :description, :image1, :image2, :image3, :image4,
                 :id_status, :id_type, :id_user)',
            $data
        );
        return (int) self::connect()->lastInsertId();
    }
}
