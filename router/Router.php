<?php
require_once __DIR__ . '/../config/config.php';

class Router
{
    public function route()
    {
        global $db; //Récupère la connexion définie dans config.php

        // Lire la page demandée dans l'URL (GET)
        $action = $_GET['action'] ?? 'home';

        switch ($action) {
            case 'home':
                require_once __DIR__ . '/../app/controllers/HomeController.php';
                $controller = new HomeController();
                $controller->home();
                break;

            case 'devis':
                require_once __DIR__ . '/../app/controllers/DevisController.php';
                $controller = new DevisController($db);
                $controller->submitDevis();
                break;

            case 'disponibilites': // Ajout de la route pour récupérer les dates et horaires disponibles
                require_once __DIR__ . '/../app/controllers/DevisController.php';
                $controller = new DevisController($db);
                $controller->getDisponibilites();
                break;

            default:
                require_once __DIR__ . '/../app/views/Accueil.php';
                break;
        }
    }
}
