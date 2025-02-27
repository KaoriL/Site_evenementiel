<?php
require_once __DIR__ . '/../models/DevisModel.php';

class DevisController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new DevisModel($db);
    }


    public function getDisponibilites()
    {
        header('Content-Type: application/json');

        $disponibilites = $this->model->fetchDisponibilites();
        echo json_encode($disponibilites);
    }


    public function submitDevis()
    {
        // Vérifier si le formulaire est soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<pre>";
            print_r($_POST); // Vérifie que les données arrivent bien
            echo "</pre>";

            // Récupérer les données du formulaire
            $data = [
                'nom' => $_POST['nom'] ?? '',
                'prenom' => $_POST['prenom'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
                'date_evenement' => $_POST['date_evenement'] ?? '',
                'rdv_date' => $_POST['rdv_date'] ?? '',
                'rdv_horaire' => $_POST['rdv_horaire'] ?? '', // Ajout de la donnée
                'service' => $_POST['service'] ?? '',
                'lieu' => $_POST['lieu'] ?? '',
                'message' => $_POST['message'] ?? '',
                'disponibilite_id' => $_POST['disponibilite_id'] ?? null,

            ];

            // Vérifie si disponibilite_id est bien transmis
            if (empty($data['disponibilite_id'])) {
                echo "Erreur : Aucun créneau sélectionné.";
                return;
            }


            // Appeler le modèle pour sauvegarder les données
            $result = $this->model->saveDevis($data);

            if ($result) {
                echo "Votre demande de devis a été envoyée avec succès !";
            } else {
                echo "Erreur lors de l'envoi de votre demande.";
            }
        } else {

            //Pour une requête GET, afficher le formulaire (la vue)
            require_once __DIR__ . '/../views/devis.php';
        }
    }

}
?>