<?php
namespace GmR\controller;

class ViewController
{
    protected function requireAuth(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }
    }

    protected function requireAdmin(): void
    {
        if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    protected function render(string $view, array $data = []): void
    {
        extract($data);
        require V_SKELETON . 'v_header.html.php';
        require $view;
        require V_SKELETON . 'v_footer.html.php';
    }

    public function home(): void
    {
        $this->render(VIEW . 'v_index.html.php', ['pageTitle' => 'Accueil']);
    }

    public function rules(): void
    {
        $this->render(V_RULES . 'v_rules.html.php', ['pageTitle' => 'Règles']);
    }

    public function info(): void
    {
        $this->render(V_INFO . 'v_info.html.php', ['pageTitle' => 'Mentions Légales']);
    }
}
