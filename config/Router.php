<?php
namespace Config;
use GmR\controller\EstateController;
use GmR\controller\GameController;
use GmR\controller\MessageController;
use GmR\controller\UserController;
use GmR\controller\ViewController; 

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
        switch ($this->page) {
            case '/':
                (new ViewController())->home();
                break;

            case '/info':
                (new ViewController())->info();
                break;

            case '/regle':
                (new ViewController())->rules();
                break;

            case '/jeu':
                (new GameController())->play();
                break;

            case '/contact':
                (new MessageController())->contact();
                break;

            case '/contact/proposer':
                (new EstateController())->propose();
                break;

            case '/auth':
                (new UserController())->auth();
                break;

            case '/profil':
                (new UserController())->profil();
                break;

            case '/profil/history':
                (new UserController())->history();
                break;

            case '/admin':
                (new UserController())->dashboard();
                break;

            case '/admin/biens':
                (new EstateController())->adminList();
                break;

            case '/admin/utilisateurs':
                (new UserController())->adminList();
                break;

            default:
                http_response_code(404);
                $pageTitle = '404 — Page introuvable';
                require V_SKELETON . 'v_header.html.php';
                require VIEW . 'v_404.html.php';
                require V_SKELETON . 'v_footer.html.php';
                break;
        }
    }
}
