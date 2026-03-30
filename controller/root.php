<?php
/*
Classe Route
Gère le routage de l'application via $_GET["action"]
*/

class Root
{

    public $action;

    public function __construct()
    {
        // Récupère l'action GET, "defaut" si aucune action n'est transmise
        $this->action = $_GET["action"] ?? "defaut";
    }

    public function rooting()
    {
        // "defaut" affiche l'index view visiteur
        switch ($this->action) {
            case "defaut":
                require VIEW . "/v_index.html.php";
                break;

        }
    }
}