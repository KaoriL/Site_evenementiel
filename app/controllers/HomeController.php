<?php
class HomeController
{


    public function home()
    {
        require __DIR__ . '/../views/accueil.php';
    }



    public function homeAdmin()
    {
        // Page d'accueil pour l'admin
        require __DIR__ . '/../views/home_admin.php';
    }

    public function homeUser()
    {
        // Page d'accueil pour l'utilisateur classique
        require __DIR__ . '/../views/accueil.php'; // Assurez-toi que cette vue existe
    }
}


?>