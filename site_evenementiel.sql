-- Création de la base de données
CREATE DATABASE IF NOT EXISTS site_evenementiel;
USE site_evenementiel;

-- Table disponibilites : stocke les jours et créneaux horaires disponibles
CREATE TABLE IF NOT EXISTS disponibilites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_disponible DATE NOT NULL,
    horaire TIME NOT NULL,
    est_reserve TINYINT(1) DEFAULT 0,
    UNIQUE(date_disponible, horaire)
);

-- Table prestations : stocke les informations des rendez-vous pour les prestations générales
CREATE TABLE IF NOT EXISTS prestations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    date_evenement DATE NOT NULL,       -- Date de l'événement pour lequel le client souhaite une prestation
    rdv_date DATE NOT NULL,             -- Date choisie pour le rendez-vous en ligne
    rdv_horaire TIME NOT NULL,          -- Créneau horaire choisi pour le rendez-vous
    service VARCHAR(50) NOT NULL,       -- Type de prestation (ex: Gala, Soirée Privée, etc.)
    lieu VARCHAR(100),                  -- Lieu de l'événement
    message TEXT,                       -- Description ou besoins supplémentaires
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    disponibilite_id INT,               -- (Optionnel) Référence vers le créneau dans la table disponibilites
    FOREIGN KEY (disponibilite_id) REFERENCES disponibilites(id)
);

-- Table prestations_mariage : stocke les informations des rendez-vous pour les mariages
CREATE TABLE IF NOT EXISTS prestations_mariage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_marie VARCHAR(50) NOT NULL,
    prenom_marie VARCHAR(50) NOT NULL,
    email_marie VARCHAR(100) NOT NULL,
    telephone_marie VARCHAR(20) NOT NULL,
    nom_mariee VARCHAR(50) NOT NULL,
    prenom_mariee VARCHAR(50) NOT NULL,
    email_mariee VARCHAR(100) NOT NULL,
    telephone_mariee VARCHAR(20) NOT NULL,
    date_evenement DATE NOT NULL,       -- Date de l'événement pour lequel le client souhaite une prestation
    rdv_date DATE NOT NULL,             -- Date choisie pour le rendez-vous en ligne
    rdv_horaire TIME NOT NULL,          -- Créneau horaire choisi pour le rendez-vous
    message TEXT,                       -- Description ou besoins supplémentaires
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    disponibilite_id INT,               -- (Optionnel) Référence vers le créneau dans la table disponibilites
    FOREIGN KEY (disponibilite_id) REFERENCES disponibilites(id)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

