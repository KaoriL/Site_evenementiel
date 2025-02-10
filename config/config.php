<?php
$host = 'localhost';     // L'adresse de ta base de données
$dbname = 'site_evenementiel';  // Le nom de ta base de données
$username = 'root';      // Ton identifiant (souvent 'root' en local)
$password = 'root';          // Ton mot de passe (souvent vide en local)

// Connexion à la base de données avec PDO
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
