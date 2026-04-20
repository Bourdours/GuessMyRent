<?php

namespace GmR\controller;

use GmR\controller\BaseController;
use GmR\model\MessageModel;

class MessageController extends BaseController
{

    /** Display the contact form and process submissions */
    public function contact(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $result = $this->handleContact();   // returns ['success' => ...] or ['error' => ...]
            $this->refreshCsrf();
            $this->render(V_CONTACT . 'v_contact.html.php', array_merge(
                ['pageTitle' => 'Contact', 'csrf_token' => $_SESSION['csrf_token'], 'activeTab' => 'message'],
                $result
            ));
            return;
        }

        // GET: preserve the active tab from the query string
        $activeTab = $_GET['tab'] ?? 'message';
        $this->refreshCsrf();
        $this->render(V_CONTACT . 'v_contact.html.php', [
            'pageTitle'  => 'Contact',
            'csrf_token' => $_SESSION['csrf_token'],
            'activeTab'  => $activeTab,
        ]);
    }

    /** Admin: list all messages and handle deletion */
    public function adminMessage(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();

            $action    = $_POST['action'] ?? '';
            $messageId = (int) ($_POST['message_id'] ?? 0);

            if ($action === 'delete' && $messageId > 0) {
                (new MessageModel())->deleteToVoid($messageId);
            }

            // PRG pattern: redirect to avoid resubmission on refresh
            header('Location: ' . BASE_URL . '/admin/messagerie');
            exit;
        }

        $this->refreshCsrf();

        $this->render(V_ADMIN . 'v_admin_message.html.php', [
            'pageTitle'  => 'Gestion des messages - Admin',
            'message'    => (new MessageModel())->findAllWithUser(),
            'csrf_token' => $_SESSION['csrf_token'],
            'pageScript' => BASE_URL . '/public/js/admin.js',
        ]);
    }

    /** Validate and persist a contact form submission; returns a success or error array */
    private function handleContact(): array
    {
        $email   = trim($_POST['email'] ?? '');
        $content = trim($_POST['content'] ?? '');
        // Only accept subjects from the predefined whitelist
        $allowed = ['Question générale', 'Signaler un bug', 'Signaler un bien incorrect', 'Autre'];
        $objet   = in_array($_POST['objet'] ?? '', $allowed, true) ? $_POST['objet'] : null;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $content === '') {
            return ['error' => 'Un email valide et un message sont requis.'];
        }

        (new MessageModel())->create($email, $content, $_SESSION['user_id'] ?? null, $objet);

        return ['success' => 'Votre message a bien été envoyé.'];
    }
}
