<?php

namespace GmR\controller;

use GmR\controller\BaseController;
use GmR\model\UserModel;
use GmR\model\GameModel;
use GmR\model\EstateModel;
use RuntimeException;

class UserController extends BaseController
{
    /** Handle login, registration, and logout routes */
    public function auth(): void
    {
        $action = $_GET['action'] ?? 'login';

        if ($action === 'logout') {
            $this->handleLogout();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            if ($action === 'register') {
                $this->handleRegister();
            } else {
                $this->handleLogin();
            }
            return;
        }

        $this->refreshCsrf();

        $flash = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);

        if ($action === 'register') {
            $this->render(V_AUTH . 'v_auth_register.html.php', array_filter([
                'csrf_token' => $_SESSION['csrf_token'],
                'pageTitle'  => 'Inscription',
                'error'      => $flash,
            ]));
            return;
        }

        $this->render(V_AUTH . 'v_auth.html.php', array_filter([
            'csrf_token' => $_SESSION['csrf_token'],
            'pageTitle'  => 'Connexion',
            'error'      => $flash,
        ]));
    }

    /** Verify credentials and start the authenticated session */
    private function handleLogin(): void
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $model    = new UserModel();
        $user     = $model->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->refreshCsrf();
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

    /** Validate registration data, hash the password, create the user, and start a session */
    private function handleRegister(): void
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $pseudo   = trim($_POST['pseudo'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8 || $pseudo === '') {
            $this->refreshCsrf();
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
            $this->refreshCsrf();
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

    /** Clear the session and redirect to the home page */
    private function handleLogout(): void
    {
        $_SESSION = [];
        session_destroy();
        header('Location: ' . BASE_URL . '/');
        exit;
    }

    /** Render the user profile with game history, stats, and leaderboard */
    public function profil(): void
    {
        $this->requireAuth();
        $this->refreshCsrf();

        $gameModel = new GameModel();
        $user      = (new UserModel())->findById($_SESSION['user_id']);
        $history   = $gameModel->findByUser($_SESSION['user_id']);

        $pseudo     = $user['username'] ?? $user['pseudo'] ?? '';
        $initial    = mb_strtoupper(mb_substr($pseudo, 0, 1));
        $totalGames = count($history);
        $totalScore = 0;
        foreach ($history as $g) {
            $totalScore += (int)$g['game_result'];
        }
        $avgScore    = $totalGames > 0 ? round($totalScore / $totalGames) : 0;
        $userRank    = $totalGames > 0 ? $gameModel->getUserRank($_SESSION['user_id']) : null;
        $leaderboard = $gameModel->leaderboard(10);

        $success = $_SESSION['flash_success'] ?? null;
        $error   = $_SESSION['flash_error']   ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        $this->render(V_PROFIL . 'v_profil.html.php', [
            'pageTitle'     => 'Mon profil',
            'user'          => $user,
            'pseudo'        => $pseudo,
            'history'       => $history,
            'initial'       => $initial,
            'totalGames'    => $totalGames,
            'totalScore'    => $totalScore,
            'avgScore'      => $avgScore,
            'userRank'      => $userRank,
            'leaderboard'   => $leaderboard,
            'baseUrl'       => BASE_URL,
            'csrf_token'    => $_SESSION['csrf_token'],
            'success' => $success,
            'error'   => $error,
            'pageScript'    => BASE_URL . '/public/js/profil.js',
        ]);
    }

    /** Update pseudo, email, and optionally password for the logged-in user */
    public function editAccount(): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/profil');
            exit;
        }

        $this->validateCsrf();

        $userId  = (int) $_SESSION['user_id'];
        $pseudo  = trim($_POST['pseudo'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $newPwd  = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($pseudo === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = 'Pseudo et email valide requis.';
            header('Location: ' . BASE_URL . '/profil');
            exit;
        }

        if ($newPwd !== '' && strlen($newPwd) < 8) {
            $_SESSION['flash_error'] = 'Le mot de passe doit contenir au moins 8 caractères.';
            header('Location: ' . BASE_URL . '/profil');
            exit;
        }

        if ($newPwd !== '' && $newPwd !== $confirm) {
            $_SESSION['flash_error'] = 'Les mots de passe ne correspondent pas.';
            header('Location: ' . BASE_URL . '/profil');
            exit;
        }

        $model = new UserModel();
        $model->updateProfile($userId, $pseudo, null);
        $model->updateEmail($userId, $email);

        if ($newPwd !== '') {
            $algo = defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_BCRYPT;
            $model->updatePassword($userId, password_hash($newPwd, $algo));
        }

        $_SESSION['pseudo']        = $pseudo;
        $_SESSION['flash_success'] = 'Profil mis à jour avec succès.';

        header('Location: ' . BASE_URL . '/profil');
        exit;
    }

    /** Delete the logged-in user's account and destroy the session */
    public function deleteAccount(): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/profil');
            exit;
        }

        $this->validateCsrf();

        $userId = (int) $_SESSION['user_id'];
        (new UserModel())->delete($userId);

        $_SESSION = [];
        session_destroy();

        header('Location: ' . BASE_URL . '/');
        exit;
    }

    /** Render the full game history page for the logged-in user */
    public function history(): void
    {
        $this->requireAuth();

        $games = (new GameModel())->findByUser($_SESSION['user_id']);

        $this->render(V_PROFIL . 'v_profil_history.html.php', [
            'pageTitle' => 'Historique',
            'games'     => $games,
        ]);
    }

    /** Admin management of users (list + deletion) */
    public function adminList(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();

            $action = $_POST['action'] ?? '';
            $userId = (int) ($_POST['user_id'] ?? 0);
            $model  = new UserModel();

            if ($action === 'update' && $userId > 0) {
                $email   = trim($_POST['email']   ?? '');
                $pseudo  = trim($_POST['pseudo']  ?? '');
                $avatar  = trim($_POST['avatar']  ?? '') ?: null;
                $isAdmin = (bool) ($_POST['is_admin'] ?? false);
                $newPwd  = $_POST['new_password'] ?? '';

                $hashed = ($newPwd !== '') ? password_hash($newPwd, PASSWORD_DEFAULT) : null;
                $model->adminUpdate($userId, $email, $pseudo, $avatar, $isAdmin, $hashed);
            }

            // Prevent admin from deleting their own account
            if ($action === 'delete' && $userId !== (int) $_SESSION['user_id']) {
                $model->delete($userId);
            }

            header('Location: ' . BASE_URL . '/admin/utilisateurs');
            exit;
        }

        $this->refreshCsrf();

        $this->render(V_ADMIN . 'v_admin_users.html.php', [
            'pageTitle'  => 'Gestion des utilisateurs - Admin',
            'users'      => (new UserModel())->findAll(),
            'csrf_token' => $_SESSION['csrf_token'],
            'pageScript' => BASE_URL . '/public/js/admin.js',
        ]);
    }

    /** Admin: render the dashboard with platform-wide statistics */
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
