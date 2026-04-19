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

    /** Compute the percentage gap between guess and actual rent */
    public static function computeGap(int $guess, int $rent): float
    {
        return abs($guess - $rent) / $rent * 100;
    }

    /** Compute score as 100 - gap%, rounded to integer, minimum 0 */
    public static function computeScore(int $guess, int $rent): int
    {
        return max(0, (int) round(100 - self::computeGap($guess, $rent)));
    }

    /** Insert a new game record and return the generated ID */
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

    /** Fetch all games played by a user with full estate details, newest first */
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

    /** Count total number of games in the database */
    public function countAll(): int
    {
        return (int) $this->executeQuery('SELECT COUNT(*) FROM GAME')->fetchColumn();
    }

    /** Average score across all scored games; returns 0 if no data */
    public function avgScore(): int
    {
        $avg = $this->executeQuery(
            'SELECT AVG(game_result) FROM GAME WHERE game_result IS NOT NULL'
        )->fetchColumn();
        return $avg !== false ? (int) round($avg) : 0;
    }

    /** Fetch all games joined with player pseudo and estate city, newest first */
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

    /** Return distinct estate IDs already played by a user */
    public function findPlayedEstateIdsByUser(int $userId): array
    {
        return $this->executeQueryWithBind(
            'SELECT DISTINCT id_estate FROM GAME WHERE id_user = :id_user',
            ['id_user' => $userId]
        )->fetchAll(\PDO::FETCH_COLUMN) ?: [];
    }

    /** Fetch top players ranked by total points */
    public function leaderboard(int $limit = 10): array
    {
        return $this->executeQueryWithBind(
            'SELECT u.pseudo, u.avatar,
                    SUM(g.game_result) AS total_pts,
                    COUNT(*)           AS nb_parties,
                    ROUND(AVG(g.game_result)) AS avg_score
             FROM GAME g
             JOIN USER u ON g.id_user = u.id_user
             WHERE g.id_user IS NOT NULL AND g.game_result IS NOT NULL
             GROUP BY g.id_user, u.pseudo, u.avatar
             ORDER BY total_pts DESC
             LIMIT :lim',
            ['lim' => $limit]
        )->fetchAll();
    }

    /** Return the leaderboard rank of a user (1-based) */
    public function getUserRank(int $userId): int
    {
        $row = $this->executeQueryWithBind(
            'SELECT COUNT(*) + 1 AS rank_pos
             FROM (
               SELECT id_user, SUM(game_result) AS total_pts
               FROM GAME
               WHERE id_user IS NOT NULL AND game_result IS NOT NULL
               GROUP BY id_user
             ) t
             WHERE t.total_pts > (
               SELECT COALESCE(SUM(game_result), 0)
               FROM GAME
               WHERE id_user = :id_user AND game_result IS NOT NULL
             )',
            ['id_user' => $userId]
        )->fetch();
        return (int) ($row['rank_pos'] ?? 1);
    }

    /** Delete all games belonging to a user */
    public function deleteByUser(int $userId): bool
    {
        return $this->executeQueryWithBind(
            'DELETE FROM GAME WHERE id_user = :id_user',
            ['id_user' => $userId]
        )->rowCount() > 0;
    }
}
