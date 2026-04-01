<?php
require_once MODEL . "connect.php";

class Base extends DbConnect
{

    /* === Lecture === */

    private const ALLOWED_TABLES = ['ESTATE', 'ESTATE_MODIFICATION', 'GAME', 'MESSAGE', 'STATUS', 'TYPE', 'USER_'];

    // récupère les valeurs de $table de la db sous forme de tableau asso
    public function getTableContent(string $table): array
    {
        if (!in_array($table, self::ALLOWED_TABLES)) {
            throw new InvalidArgumentException("Table '$table' non autorisée.");
        }

        $sql = "SELECT * FROM $table"; // $param 
        $req = self::executeQuery($sql);

        return $req->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
