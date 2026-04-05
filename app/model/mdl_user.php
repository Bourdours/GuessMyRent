<?php
require_once MODEL . "mdl_model.php";

class UserModel extends Model
{
    private int $id_user;
    private string $email;
    private string $password;
    private string $pseudo;
    private string $avatar;
    private bool $is_admin;

    function __construct()
    {
        $this->tableName = 'USER';
    }

    // public function findAll(): array
    // {
    //     return $this->executeQuery(
    //         'SELECT id_user, email, pseudo, avatar, is_admin FROM USER'
    //     )->fetchAll();
    // }

    public function findById(int $id): array|false
    {
        $query = $this->executeQueryWithBind(
            'SELECT id_user, email, pseudo, avatar, is_admin FROM USER WHERE id_user = :id LIMIT 1',
            ['id' => $id]
        );
        return $query->fetch();
    }
    
    public function findByEmail(string $email): array|false
    {
        $query = $this->executeQueryWithBind(
            'SELECT * FROM USER WHERE email = :email LIMIT 1',
            ['email' => $email]
        );
        return $query->fetch();
    }

    public function emailExists(string $email): bool
    {
        return (int) $this->executeQueryWithBind(
            'SELECT COUNT(*) FROM USER WHERE email = :email',
            ['email' => $email]
        )->fetchColumn() > 0;
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
            ['pseudo' => $pseudo, 'avatar' => $avatar, 'id' => $id]
        )->rowCount() > 0;
    }

    public function updateEmail(int $id, string $newEmail): bool
    {
        return $this->executeQueryWithBind(
            'UPDATE USER SET email = :email WHERE id_user = :id',
            ['email' => $newEmail, 'id' => $id]
        )->rowCount() > 0;
    }

    public function updatePassword(int $id, string $hashedPassword): bool
    {
        return $this->executeQueryWithBind(
            'UPDATE USER SET password = :password WHERE id_user = :id',
            ['password' => $hashedPassword, 'id' => $id]
        )->rowCount() > 0;
    }

    public function delete(int $id): void
    {
        $this->executeQueryWithBind('DELETE FROM USER WHERE id_user = :id', ['id' => $id]);
    }
}
