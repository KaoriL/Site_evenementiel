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



            case 'login':
                require_once __DIR__ . '/../app/controllers/AuthController.php';
                $controller = new AuthController($db);
                $controller->login();
                break;

            case 'register':
                require_once __DIR__ . '/../app/controllers/AuthController.php';
                $controller = new AuthController($db);
                $controller->register();
                break;

            case 'logout':
                require_once __DIR__ . '/../app/controllers/AuthController.php';
                $controller = new AuthController($db);
                $controller->logout();
                break;

            case 'devis_mariage':
                require_once __DIR__ . '/../app/controllers/DevisController.php';
                $controller = new DevisController($db);
                $controller->submitDevisMariage();
                break;

            case 'disponibilites': // Ajout de la route pour récupérer les dates et horaires disponibles
                require_once __DIR__ . '/../app/controllers/DevisController.php';
                $controller = new DevisController($db);
                $controller->getDisponibilites();
                break;

            case 'confirmation':
                require_once __DIR__ . '/../app/controllers/DevisController.php';
                $controller = new DevisController($db);
                $controller->traiterDevis();
                break;

            case 'mention-legales':
                require_once 'app/views/Mentions-legales.php';
                break;

            case 'mariage':
                require_once 'app/views/Presta_mariage.php';
                break;


            default:
                require_once __DIR__ . '/../app/views/Accueil.php';
                break;
        }
    }
}
