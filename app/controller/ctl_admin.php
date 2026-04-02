<?php
require CONTROLLER . "ctl_view.php";

class AdminController extends ViewController
{
    /** Redirige vers / si l'utilisateur n'est pas admin. */
    private function requireAdmin(): void
    {
        // . . .
    }

    public function dashboard(): void
    {
        $this->requireAdmin();
        $this->render(V_ADMIN . 'v_admin_dashboard.html.php');
    }


    public function estate(): void {
        $this->requireAdmin();
        // ...
        $this->render(V_ADMIN . 'v_admin_estate.html.php');
    }

        public function users(): void {
        $this->requireAdmin();
        // ...
        $this->render(V_ADMIN . 'v_admin_users.html.php');
    }
}
