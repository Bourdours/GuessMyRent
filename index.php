<?php
/*  DEFINITION DES CONSTANCES */
require dirname(__FILE__) . "/controller/config.php";

/* UTILISATION COMPOSER */
require VENDOR . '/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/* DEFINITION DU ROUTAGE */
require CONTROLLER . "/root.php";
$load = new Root();
$load->rooting();