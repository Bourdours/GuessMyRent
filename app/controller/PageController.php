<?php

namespace GmR\controller;

use GmR\model\EstateModel;
use GmR\model\GameModel;

class PageController extends BaseController
{
    public function home(): void
    {
        $estateModel = new EstateModel();
        $gameModel   = new GameModel();

        $stats = [
            'biens_disponibles' => $estateModel->countActive(),
            'parties_jouees'    => $gameModel->countAll(),
            'moyenne_scores'    => $gameModel->avgScore(),
            'villes_couvertes'  => $estateModel->countCities(),
        ];

        $this->render(VIEW . 'v_index.html.php', ['pageTitle' => 'Accueil', 'stats' => $stats]);
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
