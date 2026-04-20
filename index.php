<?php
session_start();

use Config\Router;

/*  CONSTANTS DEFINITION */
require dirname(__FILE__) . "/config/config.php";

/* UTILISATION COMPOSER */
require VENDOR . 'autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/* ROUTING DEFINITION */
try {
    $load = new Router();
    $load->routing();
} catch (\RuntimeException $e) {
    http_response_code(500);
    $pageTitle = '500 — Erreur serveur';
    require V_SKELETON . 'v_header.html.php';
    require VIEW . 'v_500.html.php';
    require V_SKELETON . 'v_footer.html.php';
}