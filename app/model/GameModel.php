<?php

namespace GmR\model;

use GmR\model\Model;

class GameModel extends Model
{
    public function __construct()
    {
        $this->tableName = 'GAME';
        $this->primaryKey = "id_game";
    }

    public static function computeGap(int $guess, int $rent): float
    {
        return abs($guess - $rent) / $rent * 100;
    }

    // score = 100 - gap%, arrondi à l'entier, minimum 0
    public static function computeScore(int $guess, int $rent): int
    {
        return max(0, (int) round(100 - self::computeGap($guess, $rent)));
    }

    public function create(int $guess, ?int $score, int $estateId, ?int $userId): int
    {
        $this->executeQueryWithBind(
            'INSERT INTO GAME (guess, game_result, date, id_estate, id_user)
             VALUES (:guess, :score, NOW(), :id_estate, :id_user)',
            [
                'guess'     => $guess,
                'score'     => $score,
                'id_estate' => $estateId,
                'id_user'   => $userId
            ]
        );
        return (int) self::connect()->lastInsertId();
    }

    public function findByUser(int $userId): array
    {
        return $this->executeQueryWithBind(
            'SELECT g.id_game, g.guess, g.game_result, g.date,
                    e.id_estate, e.city, e.square_meters, e.rent,
                    e.adress, e.postcode, e.description,
                    e.room, e.chamber, e.floor, e.is_charges_included,
                    e.image1, e.image2, e.image3, e.image4,
                    t.label AS type_label
             FROM GAME g
             LEFT JOIN ESTATE e ON g.id_estate = e.id_estate
             LEFT JOIN TYPE   t ON e.id_type   = t.id_type
             WHERE g.id_user = :id_user
             ORDER BY g.date DESC',
            ['id_user' => $userId]
        )->fetchAll();
    }

    public function countAll(): int
    {
        return (int) $this->executeQuery('SELECT COUNT(*) FROM GAME')->fetchColumn();
    }

    public function avgScore(): int
    {
        $avg = $this->executeQuery(
            'SELECT AVG(game_result) FROM GAME WHERE game_result IS NOT NULL'
        )->fetchColumn();
        return $avg !== false ? (int) round($avg) : 0;
    }

    public function findJoinedAll(): array
    {
        return $this->executeQuery(
            'SELECT g.id_game, g.guess, g.game_result, g.date,
                    u.pseudo, e.city
             FROM GAME g
             LEFT JOIN USER u    ON g.id_user    = u.id_user
             LEFT JOIN ESTATE e  ON g.id_estate  = e.id_estate
             ORDER BY g.date DESC'
        )->fetchAll();
    }

    public function findPlayedEstateIdsByUser(int $userId): array
    {
        return $this->executeQueryWithBind(
            'SELECT DISTINCT id_estate FROM GAME WHERE id_user = :id_user',
            ['id_user' => $userId]
        )->fetchAll(\PDO::FETCH_COLUMN) ?: [];
    }

    // Supprime toutes les parties d'un utilisateur
    public function deleteByUser(int $userId): bool
    {
        return $this->executeQueryWithBind(
            'DELETE FROM GAME WHERE id_user = :id_user',
            ['id_user' => $userId]
        )->rowCount() > 0;
    }
}
