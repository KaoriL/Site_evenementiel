<?php
// Charger le routeur
require_once __DIR__ . '/router/Router.php';
require_once __DIR__ . '/config/config.php';

// Lancer le routeur
$router = new Router();
$router->route();
?>
