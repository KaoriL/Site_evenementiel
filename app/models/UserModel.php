<?php
class UserModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function registerUser($username, $email, $password, $role = 'user')
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hachage du mot de passe
    
        $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password); // Stocke le mot de passe haché
        $stmt->bindParam(':role', $role);
    
        return $stmt->execute();
    }
    
    

    public function loginUser($email, $password) {
        // On récupère l'utilisateur par son email
        $user = $this->getUserByEmail($email);
        
        // Si l'utilisateur existe et que le mot de passe est valide
        if ($user && password_verify($password, $user['password'])) {
            // On retourne l'utilisateur avec le rôle
            return [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role'] // Ajout du rôle
            ];
        }
        
        return false; // Si l'email ou le mot de passe est incorrect
    }
    
}
?>
