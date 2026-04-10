<?php

namespace GmR\model;

use GmR\model\Model;

class UserModel extends Model
{
    function __construct()
    {
        $this->tableName = 'USER';
        $this->primaryKey = "id_user";
    }

    public function findByEmail(string $email): array|false
    {
        $query = $this->executeQueryWithBind(
            'SELECT * FROM USER WHERE email = :email LIMIT 1',
            ['email' => $email]
        );
        return $query->fetch();
    }

    public function create(
        string  $email,
        string  $hashedPassword,
        string  $pseudo,
        ?string $avatar  = null,
        bool    $isAdmin = false
    ): int {
        $this->executeQueryWithBind(
            'INSERT INTO USER (email, password, pseudo, avatar, is_admin)
             VALUES (:email, :password, :pseudo, :avatar, :is_admin)',
            [
                'email'    => $email,
                'password' => $hashedPassword,
                'pseudo'   => $pseudo,
                'avatar'   => $avatar,
                'is_admin' => (int) $isAdmin,
            ]
        );
        return (int) self::connect()->lastInsertId();
    }

    public function updateProfile(int $id, ?string $pseudo, ?string $avatar): bool
    {
        return $this->executeQueryWithBind(
            'UPDATE USER SET pseudo = :pseudo, avatar = :avatar WHERE id_user = :id',
            [
                'pseudo' => $pseudo,
                'avatar' => $avatar,
                'id' => $id
            ]
        )->rowCount() > 0;
    }

    public function updateEmail(int $id, string $newEmail): bool
    {
        return $this->executeQueryWithBind(
            'UPDATE USER SET email = :email WHERE id_user = :id',
            [
                'email' => $newEmail,
                'id' => $id
            ]
        )->rowCount() > 0;
    }

    public function updatePassword(int $id, string $hashedPassword): bool
    {
        return $this->executeQueryWithBind(
            'UPDATE USER SET password = :password WHERE id_user = :id',
            [
                'password' => $hashedPassword,
                'id' => $id
            ]
        )->rowCount() > 0;
    }

    public function delete(int $id): void
    {
        $this->executeQueryWithBind('UPDATE ESTATE SET id_user = NULL WHERE id_user = :id', ['id' => $id]);
        $this->executeQueryWithBind('UPDATE ESTATE_MODIFICATION SET id_user = NULL WHERE id_user = :id', ['id' => $id]);
        $this->executeQueryWithBind('DELETE FROM USER WHERE id_user = :id', ['id' => $id]);
    }
}
