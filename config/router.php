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

            case '/jeu':
                require CONTROLLER . 'ctl_view.php';
                (new ViewController())->play();
                break;

            case '/regle':
                require CONTROLLER . 'ctl_view.php';
                (new ViewController())->rules();
                break;

            case '/contact':
                require CONTROLLER . 'ctl_view.php';
                (new ViewController())->contact();
                break;

            case '/auth':
                require CONTROLLER . 'ctl_user.php';
                (new UserController())->auth();
                break;

            case '/contact/suggest':
                require CONTROLLER . 'ctl_user.php';
                (new UserController())->suggest();
                break;

            case '/profil':
                require CONTROLLER . 'ctl_user.php';
                (new UserController())->profil();
                break;

            case '/profil/history':
                require CONTROLLER . 'ctl_user.php';
                (new UserController())->hictory();
                break;

            case '/admin':
                require CONTROLLER . 'ctl_admin.php';
                (new AdminController())->dashboard();
                break;

            case '/admin/biens':
                require CONTROLLER . 'ctl_admin.php';
                (new AdminController())->estates();
                break;

            case '/admin/utilisateurs':
                require CONTROLLER . 'ctl_admin.php';
                (new AdminController())->users();
                break;

            default:
                http_response_code(404);
                require VIEW . 'v_404.html.php';
                break;
        }
    }
}
