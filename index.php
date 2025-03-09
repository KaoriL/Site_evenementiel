<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();  // Démarre la session si elle n'est pas encore démarrée
}
// Charger le routeur
require_once __DIR__ . '/router/Router.php';
require_once __DIR__ . '/config/config.php';

// Lancer le routeur
$router = new Router();
$router->route();
?>
