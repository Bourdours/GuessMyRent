<?php
session_start();
if (!empty($_SESSION['username'])) {
	echo "<p>Bienvenue " . $_SESSION['username'] . "</p>";
} else {
	echo "<p>Bienvenue, n'hésitez pas à vous connecter pour personnaliser votre espace</p>";
}


/*  DEFINITION DES CONSTANCES */
require dirname(__FILE__) . "/config/config.php";

/* UTILISATION COMPOSER */
require VENDOR . 'autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/* CONNECTION A LA BD */
require CONFIG . "connect.php";

/* DEFINITION DU ROUTAGE */
require CONFIG . "router.php";
$load = new Router();
$load->routing();