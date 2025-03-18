<?php
require_once __DIR__ . '/../models/AdminModel.php';

class AdminController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new AdminModel($db);
    }


    public function getPrestationsParDate()
    {
        $resultats = $this->model->getPrestationsParDate();
        $prestations = [];

        foreach ($resultats as $res) {
            // Vérification de l'ID pour savoir si c'est une prestation standard ou mariage
            $prestationType = null;
            $color = 'gray';  // Couleur par défaut si aucune prestation correspondante
            $eventId = null;
            $disponibiliteId = $res['disponibilite_id'];
            $clientEmail = null; // Initialisation de l'email

            if ($res['prestation_mariage_id']) {
                $prestationType = 'mariage';
                $color = 'pink'; // Couleur pour les prestations mariage
                $eventId = $res['prestation_mariage_id'];
                $clientEmail = $res['mariage_email'];

            } elseif ($res['prestation_id']) {
                $prestationType = 'standard';
                $color = 'green'; // Couleur pour les prestations standard
                $eventId = $res['prestation_id'];
                $clientEmail = $res['prestation_email'];
            }

            $prestations[] = [
                'id' => $eventId, // Ajout de l'ID ici
                'disponibilite_id' => $disponibiliteId, // ID de la disponibilité
                'date_disponible' => $res['date_disponible'],
                'horaire' => $res['horaire'],
                'est_reserve' => $res['est_reserve'],
                'prestation_type' => $prestationType,
                'color' => $color,// Ajouter la couleur ici
                'client_email' => $clientEmail // Ajouter l'email ici
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($prestations);
        exit;
    }

    // Action pour mettre à jour l'événement

    public function updateEvent()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data) {
                $data = $_POST; // Alternative si JSON vide
            }
            $missingData = [];
            if (!isset($data['event_id'])) {
                $missingData[] = 'event_id';
            }
            if (!isset($data['new_date'])) {
                $missingData[] = 'new_date';
            }
            if (!isset($data['new_time'])) {
                $missingData[] = 'new_time';
            }
            if (!isset($data['type'])) {
                $missingData[] = 'type';
            }
            if (!isset($data['email'])) {
                $missingData[] = 'email';
            }
            if (!empty($missingData)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Données manquantes : ' . implode(', ', $missingData)
                ]);
                return;
            }
            $event_id = $data['event_id'];
            $new_date = $data['new_date'];
            $new_time = $data['new_time'];
            $type = $data['type'];
            $email = $data['email'];
    
            // Appel du modèle pour mettre à jour l'événement
            $result = $this->model->updateEvent($event_id, $new_date, $new_time, $type);
            
            if ($result) {
                // Mise à jour réussie, maintenant on essaie d'envoyer l'email
                $emailResult = $this->model->sendEmailUpdate($email, $new_date, $new_time);
                if ($emailResult) {
                    echo json_encode(['status' => 'success', 'message' => 'Event updated and email sent']);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Email failed to send',
                        'event_id' => $event_id,
                        'new_date' => $new_date,
                        'new_time' => $new_time,
                        'email' => $email
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to update event',
                    'event_id' => $event_id,
                    'new_date' => $new_date,
                    'new_time' => $new_time,
                    'type' => $type
                ]);
            }
        }
    }
    
    // Action pour supprimer l'événement

public function deleteEvent()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $event_id = $data['event_id'];
        $type = $data['type'];  // Type de l'événement (normal ou mariage)
        // Supprimer l'événement
        $result = $this->model->deleteEvent($event_id, $type);
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Event deleted']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete event']);
        }
    }
}
    // Action pour envoyer l'email de mise à jour

    public function sendEmailUpdate()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data) {
                $data = $_POST; // Alternative si JSON vide
            }
            if (!isset($data['email'], $data['new_date'], $data['new_time'])) {
                echo json_encode(['status' => 'error', 'message' => 'Données manquantes']);
                return;
            }
            $email = $data['email'];
            $new_date = $data['new_date'];
            $new_time = $data['new_time'];
            $result = $this->model->sendEmailUpdate($email, $new_date, $new_time); // Correction ici
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Email sent']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to send email']);
            }
        }
    }
    


}
