<?php

namespace GmR\controller;

abstract class BaseController
{
    /** Redirect to auth page if the user is not logged in; optionally set a flash error */
    protected function requireAuth(?string $flashMessage = null): void
    {
        if (empty($_SESSION['user_id'])) {
            if ($flashMessage !== null) {
                $_SESSION['flash_error'] = $flashMessage;
                header('Location: ' . BASE_URL . '/auth?action=register');
            } else {
                header('Location: ' . BASE_URL . '/auth');
            }
            exit;
        }
    }

    /** Redirect to home if the user is not an admin */
    protected function requireAdmin(): void
    {
        if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    /** Abort with 403 if the POST CSRF token does not match the session token */
    protected function validateCsrf(): void
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            http_response_code(403);
            die('Requête invalide.');
        }
    }

    /** Generate a CSRF token if one does not already exist in the session */
    protected function refreshCsrf(): void
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    /** Force-rotate the CSRF token (call after each successful form submission) */
    protected function regenerateCsrf(): void
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    /** Extract $data into scope, then wrap $view with the shared header/footer skeleton */
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        require V_SKELETON . 'v_header.html.php';
        require $view;
        require V_SKELETON . 'v_footer.html.php';
    }
}
