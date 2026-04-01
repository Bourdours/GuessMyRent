<?php
require_once MODEL . "db_model.php";

class UserModel extends Model
{
    protected string $tableName = 'USER_';

    public function findByEmail(string $email): array|false
    {
        $query = $this->executeQueryWithBind(
            'SELECT * FROM USER_ WHERE email = :email LIMIT 1',
            ['email' => $email]
        );
        return $query->fetch();
    }

    public function findById(int $id): array|false
    {
        $query = $this->executeQueryWithBind(
            'SELECT id_user, email, pseudo, avatar, is_admin FROM USER_ WHERE id_user = :id LIMIT 1',
            ['id' => $id]
        );
        return $query->fetch();
    }

    public function findAll(): array
    {
        return $this->executeQuery(
            'SELECT id_user, email, pseudo, avatar, is_admin FROM USER_ ORDER BY id_user ASC'
        )->fetchAll();
    }

    public function create(string $email, string $hashedPassword, string $pseudo): int
    {
        $this->executeQueryWithBind(
            'INSERT INTO USER_ (email, password, pseudo, is_admin) VALUES (:email, :password, :pseudo, 0)',
            ['email' => $email, 'password' => $hashedPassword, 'pseudo' => $pseudo]
        );
        return (int) self::connect()->lastInsertId();
    }

    public function delete(int $id): void
    {
        $this->executeQueryWithBind('DELETE FROM USER_ WHERE id_user = :id', ['id' => $id]);
    }
}
