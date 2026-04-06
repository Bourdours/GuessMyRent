<?php
require CONTROLLER . "ctl_view.php";
require_once MODEL . "mdl_game.php";

class AdminController extends ViewController
{
    /** Redirige vers / si l'utilisateur n'est pas admin. */
    private function requireAdmin(): void
    {
        if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    public function dashboard(): void
    {
        $this->requireAdmin();

        $estateModel = new EstateModel();
        $stats = [
            'active_estates'  => count($estateModel->findActive()),
            'pending_estates' => count($estateModel->findInactive()),
            'total_users'     => count((new UserModel())->findAll()),
            'total_games'     => (new GameModel())->countAll(),
        ];

        $this->render(V_ADMIN . 'v_admin_dashboard.html.php', [
            'pageTitle' => 'Dashboard - Admin',
            'stats'     => $stats,
        ]);
    }


    public function estates(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
                http_response_code(403);
                die('Requête invalide.');
            }

            $action   = $_POST['action'] ?? '';
            $estateId = (int) ($_POST['estate_id'] ?? 0);
            $model    = new EstateModel();

            $statusMap = [
                'activate'   => 2,
                'deactivate' => 1,
                'archive'    => 3,
                'correction' => 4,
            ];
            if (isset($statusMap[$action])) {
                $model->updateStatus($estateId, $statusMap[$action]);
            }

            header('Location: ' . BASE_URL . '/admin/biens');
            exit;
        }

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $this->render(V_ADMIN . 'v_admin_estate.html.php', [
            'pageTitle'  => 'Gestion des biens - Admin',
            'estates'    => (new EstateModel())->findAll(),
            'csrf_token' => $_SESSION['csrf_token'],
        ]);
    }

    public function users(): void
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
}
