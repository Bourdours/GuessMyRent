<?php
namespace GmR\controller;

class PageController extends BaseController
{
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
