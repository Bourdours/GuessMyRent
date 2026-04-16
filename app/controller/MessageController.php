<?php

namespace GmR\controller;

use GmR\controller\BaseController;
use GmR\model\MessageModel;

class MessageController extends BaseController
{

    public function contact(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $result = $this->handleContact();
            $this->refreshCsrf();
            $this->render(V_CONTACT . 'v_contact.html.php', array_merge(
                ['pageTitle' => 'Contact', 'csrf_token' => $_SESSION['csrf_token'], 'activeTab' => 'message'],
                $result
            ));
            return;
        }

        $activeTab = $_GET['tab'] ?? 'message';
        $this->refreshCsrf();
        $this->render(V_CONTACT . 'v_contact.html.php', [
            'pageTitle'  => 'Contact',
            'csrf_token' => $_SESSION['csrf_token'],
            'activeTab'  => $activeTab,
        ]);
    }

    public function adminMessage(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();

            $action = $_POST['action'] ?? '';
            $messageId = (int) ($_POST['message_id'] ?? 0);

            if ($action === 'delete' && $messageId > 0) {
                (new MessageModel())->deleteByUser($messageId);
            }

            header('Location: ' . BASE_URL . '/admin/messagerie');
            exit;
        }

        $this->refreshCsrf();

        $this->render(V_ADMIN . 'v_admin_message.html.php', [
            'pageTitle' => 'Gestion des messages - Admin',
            'message' => (new MessageModel())->findAll(['id_message', 'email', 'content', 'id_user']),
            'csrf_token' => $_SESSION['csrf_token'],
            'pageScript' => BASE_URL . '/public/js/admin.js',
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
