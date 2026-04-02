<?php
/*
Classe Route
Gère le routage de l'application via $_GET["action"]
*/

class Router
{

    private string $page;

    public function __construct()
    {
        // Retire le préfixe du sous-dossier (ex: /localweb/GmR/public)
        // pour que les routes restent /jeu, /auth… peu importe où est installé le projet.
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        $uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if ($base !== '' && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }

        $this->page = rtrim($uri, '/') ?: '/';
    }

    public function routing(): void
    {
        // $this->page affiche l'index view visiteur par défaut
        switch ($this->page) {
            case '/':
                require CONTROLLER . 'ctl_view.php';
                (new ViewController())->home();
                break;
            case '/info':
                require CONTROLLER . 'ctl_view.php';
                (new ViewController())->info();
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
