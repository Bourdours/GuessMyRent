<?php
namespace GmR\controller;
use GmR\controller\BaseController;
use GmR\model\GameModel;
use GmR\model\EstateModel;

class GameController extends BaseController
{
    public function play(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleGuess();
            return;
        }

        $estate = (new EstateModel())->findRandomActive();

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $this->render(V_GAME . 'v_game.html.php', [
            'pageTitle'  => 'Jouer',
            'estate'     => $estate ?: null,
            'csrf_token' => $_SESSION['csrf_token'],
            'pageScript' => BASE_URL . '/public/js/game.js',
        ]);
    }

    private function handleGuess(): void
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            http_response_code(403);
            die('Requête invalide.');
        }

        $guess    = (int) ($_POST['guess'] ?? 0);
        $estateId = (int) ($_POST['estate_id'] ?? 0);

        if ($guess <= 0 || $estateId <= 0) {
            header('Location: ' . BASE_URL . '/jeu');
            exit;
        }

        $estate = (new EstateModel())->findById($estateId);
        if (!$estate) {
            header('Location: ' . BASE_URL . '/jeu');
            exit;
        }

        $score  = GameModel::computeScore($guess, (int) $estate['rent']);
        $gap    = GameModel::computeGap($guess, (int) $estate['rent']);

        $userId = $_SESSION['user_id'] ?? null;
        (new GameModel())->create($guess, $score, $estateId, $userId);

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $this->render(V_GAME . 'v_game_result.html.php', [
            'pageTitle'  => 'Résultat',
            'estate'     => $estate,
            'guess'      => $guess,
            'score'      => $score,
            'gap'        => round($gap, 1),
            'csrf_token' => $_SESSION['csrf_token'],
        ]);
    }
}
