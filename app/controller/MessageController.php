<?php
namespace GmR\controller;
use GmR\controller\ViewController;
use GmR\model\MessageModel;

class MessageController extends ViewController
{
    // GET : affiche le formulaire de contact
    // POST : envoie un message
    public function contact(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
                http_response_code(403);
                die('Requête invalide.');
            }

            $result = $this->handleContact();
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->render(V_CONTACT . 'v_contact.html.php', array_merge(
                ['pageTitle' => 'Contact', 'csrf_token' => $_SESSION['csrf_token'], 'activeTab' => 'message'],
                $result
            ));
            return;
        }

        $activeTab = $_GET['tab'] ?? 'message';
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $this->render(V_CONTACT . 'v_contact.html.php', [
            'pageTitle'  => 'Contact',
            'csrf_token' => $_SESSION['csrf_token'],
            'activeTab'  => $activeTab,
        ]);
    }

    private function handleContact(): array
    {
        $email   = trim($_POST['email'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $content === '') {
            return ['error' => 'Un email valide et un message sont requis.'];
        }

        (new MessageModel())->create($email, $content, $_SESSION['user_id'] ?? null);

        return ['success' => 'Votre message a bien été envoyé.'];
    }
}
