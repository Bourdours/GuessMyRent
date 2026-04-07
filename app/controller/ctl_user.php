<?php
require CONTROLLER . 'ctl_view.php';
require_once MODEL . 'mdl_user.php';
require_once MODEL . 'mdl_estate.php';
require_once MODEL . 'mdl_game.php';

class UserController extends ViewController
{
    public function auth(): void
    {
        $action = $_GET['action'] ?? 'login';

        if ($action === 'logout') {
            $this->handleLogout();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
                http_response_code(403);
                die('Requête invalide.');
            }
            if ($action === 'register') {
                $this->handleRegister();
            } else {
                $this->handleLogin();
            }
            return;
        }

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        if ($action === 'register') {
            $this->render(V_AUTH . 'v_auth_register.html.php', ['csrf_token' => $_SESSION['csrf_token'], 'pageTitle' => 'Inscription']);
            return;
        }

        $this->render(V_AUTH . 'v_auth.html.php', ['csrf_token' => $_SESSION['csrf_token'], 'pageTitle' => 'Connexion']);
    }

    private function handleLogin(): void
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $model    = new UserModel();
        $user     = $model->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->render(V_AUTH . 'v_auth.html.php', [
                'csrf_token' => $_SESSION['csrf_token'],
                'error'      => 'Email ou mot de passe incorrect.',
                'pageTitle'  => 'Connexion',
            ]);
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user_id']  = $user['id_user'];
        $_SESSION['pseudo']   = $user['pseudo'];
        $_SESSION['is_admin'] = (bool) $user['is_admin'];

        header('Location: ' . BASE_URL . '/profil');
        exit;
    }

    private function handleRegister(): void
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $pseudo   = trim($_POST['pseudo'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8 || $pseudo === '') {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->render(V_AUTH . 'v_auth_register.html.php', [
                'csrf_token' => $_SESSION['csrf_token'],
                'error'      => 'Données invalides. Email valide, pseudo et mot de passe (8 caractères min.) requis.',
                'pageTitle'  => 'Inscription',
            ]);
            return;
        }

        $algo   = defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_BCRYPT;
        $hashed = password_hash($password, $algo);
        $model  = new UserModel();

        try {
            $userId = $model->create($email, $hashed, $pseudo);
        } catch (RuntimeException) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->render(V_AUTH . 'v_auth_register.html.php', [
                'csrf_token' => $_SESSION['csrf_token'],
                'error'      => 'Cet email est déjà utilisé.',
                'pageTitle'  => 'Inscription',
            ]);
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user_id']  = $userId;
        $_SESSION['pseudo']   = $pseudo;
        $_SESSION['is_admin'] = false;

        header('Location: ' . BASE_URL . '/profil');
        exit;
    }

    private function handleLogout(): void
    {
        $_SESSION = [];
        session_destroy();
        header('Location: ' . BASE_URL . '/');
        exit;
    }

    public function profil(): void
    {
        $this->requireAuth();

        $user = (new UserModel())->findById($_SESSION['user_id']);

        $this->render(V_PROFIL . 'v_profil.html.php', [
            'pageTitle' => 'Mon profil',
            'user'      => $user,
        ]);
    }

    public function history(): void
    {
        $this->requireAuth();

        $games = (new GameModel())->findByUser($_SESSION['user_id']);

        $this->render(V_PROFIL . 'v_profil_history.html.php', [
            'pageTitle' => 'Historique',
            'games'     => $games,
        ]);
    }

    // Gestion admin des utilisateurs (liste + suppression)
    public function adminList(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
                http_response_code(403);
                die('Requête invalide.');
            }

            $action = $_POST['action'] ?? '';
            $userId = (int) ($_POST['user_id'] ?? 0);
            $model  = new UserModel();

            // Empêche l'admin de se supprimer lui-même
            if ($action === 'delete' && $userId !== (int) $_SESSION['user_id']) {
                $model->delete($userId);
            }

            header('Location: ' . BASE_URL . '/admin/utilisateurs');
            exit;
        }

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $this->render(V_ADMIN . 'v_admin_users.html.php', [
            'pageTitle'  => 'Gestion des utilisateurs - Admin',
            'users'      => (new UserModel())->findAll(),
            'csrf_token' => $_SESSION['csrf_token'],
        ]);
    }

    public function dashboard(): void
    {
        $this->requireAdmin();

        $stats = [
            'active_estates'  => count((new EstateModel())->findActive()),
            'pending_estates' => count((new EstateModel())->findInactive()),
            'total_users'     => count((new UserModel())->findAll()),
            'total_games'     => (new GameModel())->countAll(),
        ];

        $this->render(V_ADMIN . 'v_admin_dashboard.html.php', [
            'pageTitle' => 'Dashboard - Admin',
            'stats'     => $stats,
        ]);
    }
}
