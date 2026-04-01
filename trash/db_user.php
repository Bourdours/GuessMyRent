<?php
require_once DB . "db_gmr.php";

class User extends Base
{
    /* === Lecture === */

    private const ALLOWED_PROPERTY = ['id_user', 'password', 'pseudo', 'avatar', 'is_admin', 'email'];
    // recupère les valeurs de $property parmi ALLOWED_PROPERTY dans un tableau asso
    public function getUserContent(string $property): array
    {
        if (!in_array($property, self::ALLOWED_PROPERTY)) {
            throw new InvalidArgumentException("Attribut '$property' non autorisée.");
        }
        $sql = "SELECT $property FROM USER_";
        $req = self::executeQuery($sql);

        return $req->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getUserById(int $id): array
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID '$id' non autorisé.");
        }

        $sql = "SELECT * FROM USER_ WHERE id_user = :id_user";
        $params = ['id_user' => $id];
        $req = self::executeQueryWithBind($sql, $params, PDO::PARAM_INT);


        return $req->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function getUserByEmail(string $email): array
    {
        $sql = "SELECT * FROM USER_ WHERE email = :email";
        $params = ['email' => $email];
        $req = self::executeQueryWithBind($sql, $params, PDO::PARAM_STR);

        return $req->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }



    /* === Ecriture === */

    public function addUser(array $fields): void
    {
        $email = $fields['email'];
        $password = $fields['password'];
        $pseudo = $fields['pseudo'];

        $sql = "INSERT INTO USER_ (email, password, pseudo, is_admin) VALUES (:email, :password, :pseudo, 0)";
        $query = self::connect()->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $query->execute();
    }

    public function updateAvatarUser(array $fields, int $id): void
    {
        $avatar = $fields['avatar'];
        $sql = "UPDATE USER_ SET avatar = :avatar WHERE id_user = :id_user";
        $query = self::connect()->prepare($sql);
        $query->bindParam(':avatar', $avatar, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }

    public function deleteUserById(int $id): array
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("Attribut '$id' non autorisé.");
        }

        $sql = "DELETE FROM USER_ WHERE id_user = :id_user";
        $params = ['id_user' => $id];
        $req = self::executeQueryWithBind($sql, $params, PDO::PARAM_INT);

        return $req->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
