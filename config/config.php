<?php
define("ROOT", dirname(__DIR__));

define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));

define("CONFIG", dirname(__DIR__) . "/config/");

define("APP", dirname(__DIR__) . "/app/");
define("CONTROLLER", APP . "controller/");
define("MODEL", APP . "model/");

define("VENDOR", dirname(__DIR__) . "/vendor/");

define("VIEW", APP . "view/");
define("V_ADMIN", VIEW . "admin/");
define("V_AUTH", VIEW . "auth/");
define("V_CONTACT", VIEW . "contact/");
define("V_GAME", VIEW . "game/");
define("V_INFO", VIEW . "info/");
define("V_PROFIL", VIEW . "profil/");
define("V_RULES", VIEW . "rules/");
define("V_SKELETON", VIEW . "skeleton/");


define("PUBLIK", dirname(__DIR__) . "/public/");
define("IMG", PUBLIK . "img/");
define("CSS", PUBLIK . "css/");
define("JS", PUBLIK . "js/");
