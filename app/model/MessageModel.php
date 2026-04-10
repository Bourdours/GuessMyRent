<?php

namespace GmR\model;

use GmR\model\Model;

class MessageModel extends Model
{
    public function __construct()
    {
        $this->tableName = 'MESSAGE';
        $this->primaryKey = "id_message";
    }

    public function findByUser(int $userId): array
    {
        return $this->executeQueryWithBind(
            'SELECT * FROM MESSAGE WHERE id_user = :id_user ORDER BY id_message DESC',
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
            [
                'email' => $email,
                'content' => $content,
                'id_user' => $userId
            ]
        );
        return (int) self::connect()->lastInsertId();
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
