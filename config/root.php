<?php
/*
Classe Route
Gère le routage de l'application via $_GET["action"]
*/

class Root
{

    public $page;

    public function __construct()
    {
        // Récupère l'action GET, "defaut" si aucune action n'est transmise
        $this->page = $_GET["page"] ?? "defaut";
    }

    public function rooting()
    {
        // "defaut" affiche l'index view visiteur
        switch ($this->page) {
            case "defaut":
                require PAGE . "ctl_view.php";
                break;
            
            case "jeu":
                require VIEW . "/game/v_game.html.php" ;
                break;
                            
            case "auth":        // a modifier
                require PAGE . "ctl_user.php" ;
                break;            
            // case "":
            //     require ;
            //     break;            
            // case "":
            //     require ;
            //     break;            
            // case "":
            //     require ;
            //     break;            
            // case "":
            //     require ;
            //     break;            
            // case "":
            //     require ;
            //     break;            
            // case "":
            //     require ;
            //     break;
        }
        
    }
}