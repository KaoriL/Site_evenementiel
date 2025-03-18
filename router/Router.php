<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';

class Router
{
    public function route()
    {
        global $db; //Récupère la connexion définie dans config.php

        // Lire la page demandée dans l'URL (GET)
        $action = isset($_GET['action']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['action']) : 'home';


        // Vérifier si l'utilisateur est connecté
        $isLoggedIn = isset($_SESSION['user_id']);
        $userRole = $isLoggedIn ? $_SESSION['role'] : null;

        switch ($action) {
            case 'home':
                if ($isLoggedIn) {
                    // Rediriger selon le rôle de l'utilisateur
                    if ($userRole === 'admin') {
                        require_once __DIR__ . '/../app/controllers/HomeController.php';
                        $controller = new HomeController();
                        $controller->homeAdmin(); // Page spécifique pour l'admin
                    } else {
                        require_once __DIR__ . '/../app/controllers/HomeController.php';
                        $controller = new HomeController();
                        $controller->homeUser(); // Page spécifique pour l'utilisateur classique
                    }
                } else {
                    // Rediriger vers la page d'accueil si non connecté
                    require_once __DIR__ . '/../app/views/Accueil.php';
                }
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

            case 'rdv':
                require_once __DIR__ . '/../app/controllers/DevisController.php';
                $controller = new DevisController($db);
                $controller->getRendezVous();
                break;

            case 'non_connecter':
                require_once __DIR__ . '/../app/views/Devis.php';
                break;

            case 'presta':
                require_once __DIR__ . '/../app/views/Prestations.php';
                break;

            case 'contact':
                require_once __DIR__ . '/../app/views/Contact.php';
                break;

            case 'update_event':
                require_once __DIR__ . '/../app/controllers/AdminController.php';
                $controller = new AdminController($db);
                $controller->updateEvent();
                break;
            case 'delete_event':
                require_once __DIR__ . '/../app/controllers/AdminController.php';
                $controller = new AdminController($db);
                $controller->deleteEvent();
                break;
            case 'send_email_update':
                require_once __DIR__ . '/../app/controllers/AdminController.php';
                $controller = new AdminController($db);
                $controller->sendEmailUpdate();
                break;
            case 'getPrestationsParDate':
                require_once __DIR__ . '/../app/controllers/AdminController.php';
                $controller = new AdminController($db);
                $controller->getPrestationsParDate();
                break;
            case 'commentaires':
                require_once __DIR__ . '/../app/controllers/CommentController.php';
                $controller = new CommentController($db);
                $controller->showComments();
                break;
            // Ajouter un commentaire
            case 'addComment':
                require_once __DIR__ . '/../app/controllers/CommentController.php';
                $controller = new CommentController($db);
                $controller->submitComment();
                break;


            default:
                // Par défaut, si l'utilisateur est connecté, redirection vers la page d'accueil
                // Sinon, rediriger vers la page de login
                if ($isLoggedIn) {
                    if ($userRole === 'admin') {
                        header('Location: index.php?action=home'); // Admin
                    } else {
                        header('Location: index.php?action=home'); // Utilisateur
                    }
                } else {
                    header('Location: index.php?action=login'); // Non connecté
                }

                break;
        }
    }
}
