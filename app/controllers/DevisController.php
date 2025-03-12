<?php
require_once __DIR__ . '/../models/DevisModel.php';

class DevisController
{
    private $model;
    private $db;


    public function __construct($db)
    {
        $this->db = $db;  // La connexion à la base de données est passée au contrôleur
        $this->model = new DevisModel($db);
    }

    //Récupère les créneaux non disponible  
    public function getDisponibilites()
    {
        header('Content-Type: application/json');
        //Récuperer les créneaux disponible et reserver
        $disponibilites = $this->model->Disponibilites();
        echo json_encode($disponibilites);
    }

    public function submitDevis()
    {   // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['message'] = "Vous devez être connecté pour faire une demande de rendez-vous";
            header("Location: index.php?action=non_connecter"); // Redirige à la même page
            exit;
        }
        
        // Vérifier si le formulaire est soumis 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'user_id' => $_SESSION['user_id'], // Utilisation de l'ID utilisateur connecté
                'nom' => $_POST['nom'] ?? '',
                'prenom' => $_POST['prenom'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
                'date_evenement' => $_POST['date_evenement'] ?? NULL,
                'rdv_date' => $_POST['rdv_date'] ?? '',
                'rdv_horaire' => $_POST['rdv_horaire'] ?? '',

                // Ajout de la donnée 
                'service' => $_POST['service'] ?? '',
                'lieu' => $_POST['lieu'] ?? NULL,
                'message' => $_POST['message'] ?? '',
                'disponibilite_id' => ($_POST['disponibilite_id'] === 'undefined' || empty($_POST['disponibilite_id'])) ? null : $_POST['disponibilite_id'],
                // Ajout de la donnée type_prestation
                'type_prestation' => $_POST['type_prestation'] ?? 'standard',


            ];

         // if (empty($data['disponibilite_id'])) {
         //     // Appel à la fonction pour créer le créneau
         //     $disponibilite_id = $this->model->creerCreneau($data['rdv_date'], $data['rdv_horaire']);
         //     $data['disponibilite_id'] = $disponibilite_id;
            //}
    

            $result = $this->model->saveDevisStandard($data);
            if ($result) {
                $message = "Un nouveau rendez-vous a été pris !\n" . "\n";
                $message .= "🎤 Prestation : Standard \n";
                $message .= "📛 Nom : " . $data['nom'] . " " . $data['prenom'] . "\n";
                $message .= "📧 Email : " . $data['email'] . "\n";
                $message .= "📞 Téléphone : " . $data['telephone'] . "\n" . "\n";
                $message .= "🗓️ Date de l'événement : " . date('d-m-Y', strtotime($data['date_evenement'])) . "\n";
                $message .= "🎉 Service : " . $data['service'] . "\n";
                $message .= "📍 Lieu : " . $data['lieu'] . "\n";
                $message .= "💬 Message :  " . $data['message'] . "\n";
                $message .= "📆 Rendez-vous :  " . date('d-m-Y', strtotime($data['rdv_date'])) . " " . date('H:i', strtotime($data['rdv_horaire'])) . "\n";


                $this->model->sendTelegramNotification($message);
                $_SESSION['devis_data'] = $data;

                header("Location: index.php?action=confirmation");
                exit;
            } else {
                echo "Erreur lors de l'envoi de votre demande.";
            }
        } else {
            require_once __DIR__ . '/../views/devis.php';
        }
    }


    public function submitDevisMariage()
    { //echo "appel de la fonction submit mariage";
        //echo "Formulaire de devis mariage"; // Test d'affichage

        //var_dump($_SERVER["REQUEST_METHOD"]);
        //var_dump($_POST);

        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['message'] = "Vous devez être connecté pour faire une demande de rendez-vous";
            header("Location: formulaire_devis.php"); // Redirige à la même page
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $data = [
                'user_id' => $_SESSION['user_id'], // Utilisation de l'ID utilisateur connecté
                'nom_marie' => $_POST['nom_marie'] ?? '',
                'prenom_marie' => $_POST['prenom_marie'] ?? '',
                'nom_mariee' => $_POST['nom_mariee'] ?? '',
                'prenom_mariee' => $_POST['prenom_mariee'] ?? '',
                'email_marie' => $_POST['email_marie'] ?? '',
                'email_mariee' => $_POST['email_mariee'] ?? '',
                'telephone_marie' => $_POST['telephone_marie'] ?? '',
                'telephone_mariee' => $_POST['telephone_mariee'] ?? '',
                'age_marie' => $_POST['age_marie'] ?? NULL,
                'age_mariee' => $_POST['age_mariee'] ?? NULL,
                'origine_marie' => $_POST['origine_marie'] ?? '',
                'origine_mariee' => $_POST['origine_mariee'] ?? '',
                'date_evenement' => $_POST['date_evenement'] ?? NULL,
                'rdv_date' => $_POST['rdv_date'] ?? '',
                'rdv_horaire' => $_POST['rdv_horaire'] ?? '',

                // Ajout de la donnée 
                'service' => $_POST['service'] ?? '',
                'lieu' => $_POST['lieu'] ?? NULL,
                'message' => $_POST['message'] ?? '',
                'disponibilite_id' => ($_POST['disponibilite_id'] === 'undefined' || empty($_POST['disponibilite_id'])) ? null : $_POST['disponibilite_id'],
                'type_prestation' => 'mariage',

            ];


            $result = $this->model->saveDevisMariage($data);
            if ($result) {
                // Prépare et envoie la notification Telegram
                $message = "Un nouveau rendez-vous a été pris !\n" . "\n";

                $message .= "💍 Prestation : Mariage \n";
                $message .= "🤵🏽‍♂️ Information du marié: " . $data['nom_marie'] . " " . $data['prenom_marie'] . "\n";
                $message .= "🎂 Âge: " . $data['age_marie'] . "\n";
                $message .= "🌍 Origine : " . $data['origine_marie'] . "\n";
                $message .= "📧 Email : " . $data['email_marie'] . "\n";
                $message .= "📞 Téléphone : " . $data['telephone_marie'] . "\n" . "\n";

                $message .= "👰🏽‍♀️ Information du marié: " . $data['nom_mariee'] . " " . $data['prenom_mariee'] . "\n";
                $message .= "🎂 Âge: " . $data['age_mariee'] . "\n";
                $message .= "🌍 Origine : " . $data['origine_marie'] . "\n";
                $message .= "📧 Email : " . $data['email_mariee'] . "\n";
                $message .= "📞 Téléphone : " . $data['telephone_mariee'] . "\n" . "\n";


                $message .= "🗓️ Date de l'événement : " . date('d-m-Y', strtotime($data['date_evenement'])) . "\n";
                $message .= "🎉 Service : " . $data['service'] . "\n";
                $message .= "📍 Lieu : " . $data['lieu'] . "\n";
                $message .= "💬 Message : " . $data['message'] . "\n";
                $message .= "📆 Rendez-vous : " . date('d-m-Y', strtotime($data['rdv_date'])) . " " . date('H:i', strtotime($data['rdv_horaire'])) . "\n";


                $this->model->sendTelegramNotification($message);
                $_SESSION['devis_data'] = $data;
                header("Location: index.php?action=confirmation");
                exit;

                if ($success) {
                    echo "Devis mariage envoyé avec succès !";
                } else {
                    echo "Erreur lors de l'envoi du devis.";
                }
            } else {
                require_once __DIR__ . '/../views/devis.php';
            }
        }
        require_once __DIR__ . '/../views/devis.php'; //
    }

    public function traiterDevis()
    {
        if (!isset($_SESSION['devis_data'])) {
            echo "Aucune donnée de devis trouvée.";
            return;
        }

        $data = $_SESSION['devis_data'];
        unset($_SESSION['devis_data']); // Supprime les données après utilisation


        // Vérifie si c'est une demande de mariage
        if (isset($data['type_prestation']) && $data['type_prestation'] === 'mariage') {
            $mailEnvoye = $this->model->envoyerMailConfirmationMariage($data);
        } else {
            $mailEnvoye = $this->model->envoyerMailConfirmation($data);
        }

        if ($mailEnvoye) {
            require_once __DIR__ . '/../views/confirmation.php';
        } else {
            echo "Erreur lors de l'envoi du mail.";
        }
    }

    public function getRendezVous()
    {
        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            echo "Erreur : utilisateur non connecté.";
            return;
        }

        $user_id = $_SESSION['user_id'];
        $rendez_vous = $this->model->fetchRendezVous($user_id); // Récupère les rendez-vous avec l'ID utilisateur

        require_once __DIR__ . '/../views/rdv.php';  // Passe la variable à la vue
    }






}

?>