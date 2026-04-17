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

        $userId = $_SESSION['user_id'] ?? null;

        // Visiteurs non inscrits : limiter à 1 partie
        if (!$userId && ($_SESSION['guest_games_count'] ?? 0) >= 1) {
            $this->refreshCsrf();
            $this->render(V_GAME . 'v_game.html.php', [
                'pageTitle'    => 'Jouer',
                'estate'       => null,
                'csrf_token'   => $_SESSION['csrf_token'],
                'avgRent'      => 0,
                'pageScript'   => BASE_URL . '/public/js/game.js',
                'guestBlocked' => true,
            ]);
            return;
        }

        $estateModel = new EstateModel();

        // Exclure les biens déjà joués par l'utilisateur connecté
        $excludeIds = $userId ? (new GameModel())->findPlayedEstateIdsByUser($userId) : [];
        $estate     = $estateModel->findRandomActive($excludeIds);
        $avgRent    = $estateModel->avgRent();

        $this->refreshCsrf();

        $this->render(V_GAME . 'v_game.html.php', [
            'pageTitle'    => 'Jouer',
            'estate'       => $estate ?: null,
            'csrf_token'   => $_SESSION['csrf_token'],
            'avgRent'      => $avgRent,
            'pageScript'   => BASE_URL . '/public/js/game.js',
            'guestBlocked' => false,
        ]);
    }

    public function adminList(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();

            $action = $_POST['action'] ?? '';
            $gameId = (int) ($_POST['game_id'] ?? 0);

            if ($action === 'delete' && $gameId > 0) {
                (new GameModel())->deleteToVoid($gameId);
            }

            header('Location: ' . BASE_URL . '/admin/parties');
            exit;
        }

        $this->refreshCsrf();

        $this->render(V_ADMIN . 'v_admin_games.html.php', [
            'pageTitle'  => 'Gestion des parties - Admin',
            'games'      => (new GameModel())->findJoinedAll(),
            'csrf_token' => $_SESSION['csrf_token'],
            'pageScript' => BASE_URL . '/public/js/admin.js',
        ]);
    }

    private function handleGuess(): void
    {
        $this->validateCsrf();

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

        // Incrémenter le compteur de parties pour les visiteurs non inscrits
        if (!$userId) {
            $_SESSION['guest_games_count'] = ($_SESSION['guest_games_count'] ?? 0) + 1;
        }

        $this->refreshCsrf();

        $this->render(V_GAME . 'v_game_result.html.php', [
            'pageTitle'       => 'Résultat',
            'estate'          => $estate,
            'guess'           => $guess,
            'score'           => $score,
            'gap'             => round($gap, 1),
            'csrf_token'      => $_SESSION['csrf_token'],
            'pageScript'      => BASE_URL . '/public/js/game.js',
            'isGuestLimited'  => !$userId,
        ]);
    }
}
