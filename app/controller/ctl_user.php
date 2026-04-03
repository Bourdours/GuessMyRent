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

        $this->render(V_AUTH . 'v_auth.html.php', ['pageTitle' => 'Connexion']);
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
