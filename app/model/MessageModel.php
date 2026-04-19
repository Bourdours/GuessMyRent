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

    /** Fetch all messages joined with sender pseudo, newest first */
    public function findAllWithUser(): array
    {
        return $this->executeQueryWithBind(
            'SELECT m.id_message, m.email, m.objet, m.content, m.id_user, u.pseudo
             FROM MESSAGE m
             LEFT JOIN USER u ON u.id_user = m.id_user
             ORDER BY m.id_message DESC',
            []
        )->fetchAll();
    }

    /** Fetch all messages sent by a specific user, newest first */
    public function findByUser(int $userId): array
    {
        return $this->executeQueryWithBind(
            'SELECT * FROM MESSAGE WHERE id_user = :id_user ORDER BY id_message DESC',
            ['id_user' => $userId]
        )->fetchAll();
    }

    /** Insert a new contact message and return the generated ID */
    public function create(
        string $email,
        string $content,
        ?int $userId = null,
        ?string $objet = null
    ): int {
        $this->executeQueryWithBind(
            'INSERT INTO MESSAGE (email, objet, content, id_user) VALUES (:email, :objet, :content, :id_user)',
            [
                'email'   => $email,
                'objet'   => $objet,
                'content' => $content,
                'id_user' => $userId
            ]
        );
        return (int) self::connect()->lastInsertId();
    }

    /** Delete all messages belonging to a user */
    public function deleteByUser(int $userId): bool
    {
        return $this->executeQueryWithBind(
            'DELETE FROM MESSAGE WHERE id_user = :id_user',
            ['id_user' => $userId]
        )->rowCount() > 0;
    }
}
