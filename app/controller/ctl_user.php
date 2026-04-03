<?php

require CONTROLLER . "ctl_view.php";

class UserController extends ViewController
{
    private function requireAuth(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /auth');
            exit;
        }
    }

    public function auth(): void
    {
        // . . .

        $this->render(V_AUTH . 'v_auth.html.php');
    }

    public function suggest(): void
    {
        $this->requireAuth();

        // . . .

        $this->render(V_CONTACT . 'v_contact_form.html.php');
    }

    public function profil(): void
    {
        $this->requireAuth();

        // . . .

        $this->render(V_PROFIL . 'v_profil.html.php');
    }

    public function hictory(): void
    {
        $this->requireAuth();

        // . . .

        $this->render(V_PROFIL . 'v_profil_history.html.php');
    }
}
