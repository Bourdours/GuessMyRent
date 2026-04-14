<?php

namespace GmR\model;

use PDO;
use PDOException;

/**
 *  Classe principale de l'objet de connexion PDO
 */
abstract class DbConnect
{
    private static ?PDO $pdo = null;

    protected static function connect(): PDO
    {
        if (self::$pdo === null) {
            try {
                $dsn = 'mysql:host=' . $_ENV['DB_HOST']
                    . ';dbname=' . $_ENV['DB_NAME']
                    . ';charset=utf8mb4';

                self::$pdo = new PDO($dsn, $_ENV['DB_LOGIN'], $_ENV['DB_PASSWORD'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                // Ne jamais exposer les détails de connexion
                die('Erreur de connexion à la base de données.');
            }
        }

        return self::$pdo;
    }
}
