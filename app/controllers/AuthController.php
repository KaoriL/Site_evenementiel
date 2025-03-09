<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/UserModel.php';

class AuthController
{
    private $userModel;
    private $errors = [];

    public function __construct($db)
    {
        $this->userModel = new UserModel($db);
    }

    public function login()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $user = $this->userModel->loginUser($email, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['id']; // Utiliser 'user_id' au lieu de 'user'
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            header('Location: index.php?action=home');
            exit;
        } else {
            $this->errors[] = "Email ou mot de passe incorrect.";
        }
    }
    $errors = $this->errors;
    require_once __DIR__ . '/../views/login.php';
}


    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $errors = []; // Initialisation des erreurs

            // Vérification des champs vides
            if (empty($username) || empty($email) || empty($password)) {
                $this->errors[] = "Tous les champs doivent être remplis.";
            }

            // Vérification de l'email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = "Adresse email invalide.";
            }

            // Vérification si l'email existe déjà
            if ($this->userModel->getUserByEmail($email)) {
                $this->errors[] = "Cet email est déjà utilisé.";
            }

            // Vérification de la robustesse du mot de passe
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
                $this->errors[] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.";
            }

            // Vérification si l'username existe déjà
            if ($this->userModel->getUserByUsername($username)) {
                $this->errors[] = "Ce nom d'utilisateur est déjà pris.";
            }

            // Si pas d'erreurs, on enregistre l'utilisateur
            if (empty($this->errors)) {
                if ($this->userModel->registerUser($username, $email, $password)) {
                    $_SESSION['user'] = [
                        'username' => $username,
                        'email' => $email
                    ];
                    header('Location: index.php?action=home');
                    exit;
                } else {
                    $this->errors[] = "Erreur lors de l'inscription.";
                }
            }
            $errors = $this->errors;
            require_once __DIR__ . '/../views/login.php';
        } else {
            
            require_once __DIR__ . '/../views/login.php';
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: index.php?action=home');
        exit;
    }
}
?>