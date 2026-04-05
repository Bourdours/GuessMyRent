<?php

require CONTROLLER . "ctl_view.php";

class UserController extends ViewController
{
    private function requireAuth(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }
    }

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
                'pageTitle' => 'Connexion'
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
                'pageTitle' => 'Inscription'
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
                'pageTitle' => 'Inscription'
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

    public function suggest(): void
    {
        $this->requireAuth();

        // . . .

        $this->render(V_CONTACT . 'v_contact_form.html.php', ['pageTitle' => 'Suggérer un bien']);
    }

    public function profil(): void
    {
        $this->requireAuth();

        // . . .

        $this->render(V_PROFIL . 'v_profil.html.php', ['pageTitle' => 'Mon profil']);
    }

    public function hictory(): void
    {
        $this->requireAuth();

        // . . .

        $this->render(V_PROFIL . 'v_profil_history.html.php', ['pageTitle' => 'Historique']);
    }
}
