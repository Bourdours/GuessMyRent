<?php
require CONTROLLER . 'ctl_view.php';
require_once MODEL . 'mdl_game.php';
require_once MODEL . 'mdl_estate.php';

class GameController extends ViewController
{
    public function play(): void
    {
        $this->render(V_GAME . 'v_game.html.php', ['pageTitle' => 'Jouer']);
    }
}
