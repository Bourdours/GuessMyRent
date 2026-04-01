<?php
require_once MODEL . "db_model.php";

class GameModel extends Model
{
    protected string $tableName = 'GAME';

    /**
     * Calcul du score — section 2.3 du cahier des charges.
     *
     * écart < 5%   → 100 pts
     * écart 5–10%  →  80 pts
     * écart 10–20% →  50 pts
     * écart > 20%  →  20 pts
     */
    public static function computeScore(int $guess, int $rent): int
    {
        $gap = abs($guess - $rent) / $rent;

        return match (true) {
            $gap < 0.05 => 100,
            $gap < 0.10 => 80,
            $gap < 0.20 => 50,
            default     => 20,
        };
    }

    public function save(int $guess, int $score, int $estateId, ?int $userId): int
    {
        $this->executeQueryWithBind(
            'INSERT INTO GAME (guess, game_result, date_, id_estate, id_user)
             VALUES (:guess, :score, NOW(), :id_estate, :id_user)',
            ['guess' => $guess, 'score' => $score, 'id_estate' => $estateId, 'id_user' => $userId]
        );
        return (int) self::connect()->lastInsertId();
    }

    public function findByUser(int $userId): array
    {
        return $this->executeQueryWithBind(
            'SELECT g.id_game, g.guess, g.game_result, g.date_,
                    e.city, e.square_meters, e.rent, e.image1
             FROM GAME g
             JOIN ESTATE e ON g.id_estate = e.id_estate
             WHERE g.id_user = :id_user
             ORDER BY g.date_ DESC',
            ['id_user' => $userId]
        )->fetchAll();
    }
}
