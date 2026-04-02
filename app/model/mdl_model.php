<?php
require_once CONFIG . "connect.php";

abstract class Model extends DbConnect
{
    protected $tableName;

    // Exécute une requête SQL
    protected function executeQuery(string $sql): PDOStatement
    {
        try {var_dump($sql);
            $query = self::connect()->prepare($sql); // récupère la connexion PDO à la DB // compilation requete SQL protection injections
            $query->execute();
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
	$sql => La requête SQL à exécuter
	PDOStatement => Retourne un objet résultat PDO 
	*/

    // Exécute une requête SQL éventuellement paramétrée, avec un binding de valeur
    protected function executeQueryWithBind(
        string $sql,
        array $params = [],
    ): PDOStatement {
        try {
            $pdo   = self::connect();
            $query = $pdo->prepare($sql);

            foreach ($params as $key => $value) {
                if ($value === null) {
                    $type = PDO::PARAM_NULL;
                } elseif (is_int($value)) {
                    $type = PDO::PARAM_INT;
                } else {
                    $type = PDO::PARAM_STR;
                }
                $query->bindValue(':' . $key, $value, $type);
            }
            $query->execute();

            return $query;
        } catch (Exception $e) {
            throw new RuntimeException(
                "Impossible de récupérer les données : " . $e->getMessage(),
                0,
                $e
            );
        }
    }

    // retourne un array de $this
    protected function getProperties(): array
    {
        var_dump($this);
        return get_object_vars($this);
    }

    // transforme l'array en string
    protected function getPropertiesNamesString(): string
    {
        return implode(',', array_keys($this->getProperties()));
    }

    function findAll(): array
    {
        $sql = "SELECT {$this->getPropertiesNamesString()} FROM {$this->tableName}";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();        
    }
}
