<?php
require CONTROLLER . "ctl_view.php";

class AdminController extends ViewController
{
    /** Redirige vers / si l'utilisateur n'est pas admin. */
    private function requireAdmin(): void
    {
        if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
            header('Location: /');
            exit;
        }
    }

    public function dashboard(): void
    {
        $this->requireAdmin();
        $this->render(V_ADMIN . 'v_admin_dashboard.html.php', ['pageTitle' => 'Dashboard']);
    }


    public function estate(): void
    {
        $this->requireAdmin();
        // ...
        $this->render(V_ADMIN . 'v_admin_estate.html.php', ['pageTitle' => 'Gestion des biens']);
    }

    public function users(): void
    {
        $this->requireAdmin();
        // ...
        $this->render(V_ADMIN . 'v_admin_users.html.php', ['pageTitle' => 'Gestion des utilisateurs']);
    }
}
