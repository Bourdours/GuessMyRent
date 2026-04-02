<?php
require_once MODEL . "mdl_model.php";

class MessageModel extends Model
{
    protected string $tableName = 'MESSAGE';

    // public function findAll(): array
    // {
    //     return $this->executeQuery(
    //         'SELECT m.*, u.pseudo, u.avatar
    //          FROM MESSAGE m
    //          JOIN USER_ u ON m.id_user = u.id_user
    //          ORDER BY m.Id_message DESC'
    //     )->fetchAll();
    // }

    public function findById(int $id): array|false
    {
        return $this->executeQueryWithBind(
            'SELECT m.*, u.pseudo
             FROM MESSAGE m
             JOIN USER_ u ON m.id_user = u.id_user
             WHERE m.Id_message = :id',
            ['id' => $id]
        )->fetch();
    }

    public function findByUser(int $userId): array
    {
        return $this->executeQueryWithBind(
            'SELECT * FROM MESSAGE WHERE id_user = :id_user ORDER BY Id_message DESC',
            ['id_user' => $userId]
        )->fetchAll();
    }

    public function create(
        string $email,
        string $content,
        ?int $userId = null
    ): int {
        $this->executeQueryWithBind(
            'INSERT INTO MESSAGE (email, content, id_user) VALUES (:email, :content, :id_user)',
            ['email' => $email, 'content' => $content, 'id_user' => $userId]
        );
        return (int) self::connect()->lastInsertId();
    }

    public function delete(int $id): bool
    {
        return $this->executeQueryWithBind(
            'DELETE FROM MESSAGE WHERE Id_message = :id',
            ['id' => $id]
        )->rowCount() > 0;
    }

    // Supprime tous les messages d'un utilisateur
    public function deleteByUser(int $userId): bool
    {
        return $this->executeQueryWithBind(
            'DELETE FROM MESSAGE WHERE id_user = :id_user',
            ['id_user' => $userId]
        )->rowCount() > 0;
    }
}
