<?php
require 'vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Vérification des variables d'environnement
//var_dump($_ENV);
//var_dump(getenv('DB_USER'));

// Affichage des variables d'environnement
//echo $_ENV['DB_HOST']; // localhost
//echo $_ENV['DB_USER']; // Affiche "root"

// Variables pour la connexion à la base de données
$dbHost = $_ENV['DB_HOST'];
$dbUser = $_ENV['DB_USER'];
$dbName = $_ENV['DB_NAME'];
$dbPass = $_ENV['DB_PASS'];

// Connexion à la base de données
try {
    $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
