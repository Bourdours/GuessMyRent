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
$load = new Router();
$load->routing();