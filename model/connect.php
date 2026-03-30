<?php

/**
 *  Classe principale de l'objet de connexion PDO
 */
abstract class DbConnect
{

	protected static function connect()
	{

		/* 
			*  Objet PDO Connexion 
			*  https://www.php.net/manual/fr/pdo.construct.php
			*/
	
		try {
			$dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'];
			$pdo = new PDO($dsn, $_ENV['DB_LOGIN'], $_ENV['DB_PASSWORD'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $pdo;
		} catch (PDOException $e) {
			die("<br />Erreur de connexion PDO !");
		}
	}
	// Exécute une requête SQL éventuellement paramétrée
	protected static function executeQuery(string $sql, array $params = []): PDOStatement
	{
		try {
			$query = self::connect()->prepare($sql); // récupère la connexion PDO à la DB // compilation requete SQL protection injections
			$query->execute($params);
			return $query;
		} catch (Exception $e) {
			throw new RuntimeException(
				"Impossible de récupérer les données : " . $e->getMessage(),
				0,
				$e
			);
		}
	}
	/*
	protected => Accessible uniquement depuis la classe et ses enfants
	static => Appelable sans instancier la classe
	$sql => La requête SQL à exécuter
	$params => Les valeurs à injecter dans la requête (optionnel)
	PDOStatement => Retourne un objet résultat PDO 
	*/
}
