<?php
session_start();

use Config\Router;

/*  DEFINITION DES CONSTANCES */
require dirname(__FILE__) . "/config/config.php";

/* UTILISATION COMPOSER */
require VENDOR . 'autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/* DEFINITION DU ROUTAGE */
$load = new Router();
$load->routing();