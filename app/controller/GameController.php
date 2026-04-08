<?php
namespace GmR\controller;
use GmR\controller\ViewController;
use GmR\model\GameModel;
use GmR\model\EstateModel;

class GameController extends ViewController
{
    public function play(): void
    {
        $this->render(V_GAME . 'v_game.html.php', ['pageTitle' => 'Jouer']);
    }
}
