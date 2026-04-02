<?php
require_once MODEL . "mdl_user.php";
require_once MODEL . "mdl_estate.php";

class ViewController
{
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        require V_SKELETON . 'v_header.html.php';
        require $view;
        require V_SKELETON . 'v_footer.html.php';
    }

    public function home(): void
    {
        $user = new UserModel();
        var_dump($user->findAll());

        $this->render(VIEW . 'v_index.html.php');
    }

    public function play(): void
    {
        $this->render(V_GAME . 'v_game.html.php');
    }

    public function rules(): void
    {
        $this->render(V_RULES . 'v_rules.html.php');
    }

    public function contact(): void
    {
        $this->render(V_CONTACT . 'v_contact.html.php');
    }

    public function info(): void
    {
        $this->render(V_INFO . 'v_info.html.php');
    }
}
