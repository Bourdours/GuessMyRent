<?php
define("ROOT", dirname(__DIR__));
define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
define("CONFIG", dirname(__DIR__) . "/config/");

define("APP", dirname(__DIR__). "/app/");

define("CONTROLLER", dirname(__DIR__) . "/app/controller/");
define("PAGE", dirname(__DIR__) . "/app/controller/page/");

define("MODEL", dirname(__DIR__) . "/app/model/");
define("DB", dirname(__DIR__) . "/app/model/db/");

define("VENDOR", dirname(__DIR__) . "/vendor/");

define("VIEW", dirname(__DIR__) . "/app/view/");
define("V_ADMIN", VIEW . "admin/");
define("V_AUTH", VIEW . "auth/");
define("V_CONTACT", VIEW . "contact/");
define("V_GAME", VIEW . "game/");
define("V_INFO", VIEW . "info/");
define("V_MENU", VIEW . "menu/");
define("V_PROFIL", VIEW . "profil/");
define("V_RULES", VIEW . "rules/");
define("V_SKELETON", VIEW . "skeleton/");


define("STATIC", dirname(__DIR__) . "/static/");
define("IMG", dirname(__DIR__) . "/static/img/");
define("CSS", dirname(__DIR__) . "/static/css/");
define("JS", dirname(__DIR__) . "/static/js/");