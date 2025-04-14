<?php
class HomeController
{


    public function home()
    {
        require __DIR__ . '/../views/Accueil.php';
    }



    
    
    
    
    

    public function homeUser()
    {
        // Page d'accueil pour l'utilisateur classique
        require __DIR__ . '/../views/Accueil.php'; // Assurez-toi que cette vue existe
    }
}


?>