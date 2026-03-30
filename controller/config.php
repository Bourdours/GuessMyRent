<?php
define("ROOT", dirname(__DIR__));

define("CONTROLLER", dirname(__DIR__) . "/controller");

define("MODEL", dirname(__DIR__) . "/model");
define("DB", dirname(__DIR__) . "/model/db");

define("VIEW", dirname(__DIR__) . "/view");
define("ADMIN", dirname(__DIR__) . "/view/admin");
define("AUTH", dirname(__DIR__) . "/view/auth");
define("CONTACT", dirname(__DIR__) . "/view/contact");
define("GAME", dirname(__DIR__) . "/view/game");
define("INFO", dirname(__DIR__) . "/view/info");
define("MENU", dirname(__DIR__) . "/view/menu");
define("PROFIL", dirname(__DIR__) . "/view/profil");
define("RULES", dirname(__DIR__) . "/view/rules");
define("SKELETON", dirname(__DIR__) . "/view/skeleton");


define("STATIC", dirname(__DIR__) . "/static");
define("IMG", dirname(__DIR__) . "/static/img");
define("CSS", dirname(__DIR__) . "/static/css");
define("JS", dirname(__DIR__) . "/static/js");



//SGBDR :
const DB_USERNAME = 'User1'; // nom d'utilisateur
const DB_PASSWORD = 'User1'; // mot de passe de l'utilisateur
const DB_DATABASE = 'GmR'; // nom de la base de données
const DB_HOST = 'localhost'; //adresse du SGBDR