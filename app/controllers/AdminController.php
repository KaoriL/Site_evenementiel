<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/../models/AdminModel.php';
require_once __DIR__ . '/../models/CommentModel.php';

class AdminController
{
    private $model;
    private $commentModel;

    public function __construct($db)
    {
        $this->model = new AdminModel($db);
        $this->commentModel = new CommentModel($db);
    }


    public function getPrestationsParDate()
    {
        $resultats = $this->model->getPrestationsParDate();

        if ($resultats === false) {
            http_response_code(500);
            echo json_encode(["error" => "Une erreur est survenue lors de la récupération des prestations."]);
            exit;
        }

        $prestations = [];

        foreach ($resultats as $res) {
            $prestationType = null;
            $color = 'gray';
            $eventId = null;
            $clientEmail = null;
            $nom = null;
            $prenom = null;
            $telephone = null;
            $service = null;
            $lieu = null;
            $date_evenement = null;
            $origine = null;

            if ($res['prestation_mariage_id']) {
                $prestationType = 'mariage';
                $color = '#C17C74';
                $eventId = $res['prestation_mariage_id'];
                $clientEmail = $res['email_mariee']; // Mettre aussi l'email du mari ?

                // Infos des mariés
                $nomMarie = $res['nom_marie'];
                $prenomMarie = $res['prenom_marie'];
                $nomMariee = $res['nom_mariee'];
                $prenomMariee = $res['prenom_mariee'];

                // Téléphones (au cas où les deux en ont un)
                $telephoneMarie = $res['telephone_marie'];
                $telephoneMariee = $res['telephone_mariee'];

                // Services, lieu et date
                $service = $res['service_mariage'];
                $lieu = $res['lieu_mariage'];
                $date_evenement = $res['date_evenement_mariage'];

                // Origines des mariés
                $origineMarie = $res['origine_marie'];
                $origineMariee = $res['origine_mariee'];
            } elseif ($res['prestation_id']) {
                $prestationType = 'standard';
                $color = '#5C9279';
                $eventId = $res['prestation_id'];
                $clientEmail = $res['prestation_email'];
                $nom = $res['nom'];
                $prenom = $res['prenom'];
                $telephone = $res['telephone'];
                $service = $res['service'];
                $lieu = $res['lieu'];
                $date_evenement = $res['date_evenement'];
            }

            $prestations[] = [
                'id' => $eventId,
                'disponibilite_id' => $res['disponibilite_id'],
                'date_disponible' => $res['date_disponible'],
                'horaire' => $res['horaire'],
                'est_reserve' => $res['est_reserve'],
                'prestation_type' => $prestationType,
                'color' => $color,
                'client_email' => $clientEmail,
                'nom' => $nom ?? null,
                'prenom' => $prenom ?? null,
                'telephone' => $telephone ?? null,
                'nom_marie' => $nomMarie ?? null,
                'prenom_marie' => $prenomMarie ?? null,
                'nom_mariee' => $nomMariee ?? null,
                'prenom_mariee' => $prenomMariee ?? null,
                'telephone_marie' => $telephoneMarie ?? null,
                'telephone_mariee' => $telephoneMariee ?? null,
                'service' => $service,
                'lieu' => $lieu,
                'date_evenement' => $date_evenement,
                'origine_marie' => $origineMarie ?? null,
                'origine_mariee' => $origineMariee ?? null
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
            // Récupérer les données envoyées
            $data = json_decode(file_get_contents('php://input'), true);
            $event_id = $data['event_id'] ?? null;
            $type = $data['type'] ?? null;
            $email = $data['email'] ?? null;

            // Vérifier si toutes les infos sont présentes
            if (!$event_id || !$type || !$email) {
                error_log(" Données manquantes dans deleteEvent: " . json_encode($data));
                echo json_encode(['status' => 'error', 'message' => 'Missing event_id, type, or email']);
                return;
            }

            error_log(" Suppression de l'événement ID: $event_id, Type: $type, Email: $email\n");

            // Supprimer l'événement en base
            $result = $this->model->deleteEvent($event_id, $type);
            if ($result) {
                error_log(" Événement $event_id supprimé avec succès\n");

                // Envoi d'un email après suppression
                $emailResult = $this->model->sendEmailUpdateDelete($email);
                if ($emailResult) {
                    error_log(" Email envoyé à $email après suppression\n");
                    echo json_encode(['status' => 'success', 'message' => 'Event deleted and email sent']);
                } else {
                    error_log(" Échec de l'envoi de l'email à $email\n");
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Email failed to send',
                        'email' => $email
                    ]);
                }
            } else {
                error_log(" Échec de la suppression de l'événement ID");
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete event']);
            }
        } else {
            error_log(" Requête invalide (pas POST) dans deleteEvent");
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
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
    public function getPendingCommentsCount()
    {
        // Appel du modèle pour récupérer le nombre de commentaires en attente
        $count = $this->commentModel->getPendingCommentsCount();

        // Retourne le résultat sous forme JSON
        echo json_encode(['count' => $count]);
        exit;  // Important pour arrêter l'exécution après l'envoi de la réponse
    }
    public function pendingComments()
    {
        try {
            // Vérifier si l'ID est passé dans l'URL
            if (isset($_GET['id'])) {
                $commentId = $_GET['id']; // Récupérer l'ID du commentaire
                $comment = $this->commentModel->getCommentById($commentId); // Récupérer le commentaire spécifique
                if ($comment) {
                    echo json_encode($comment); // Retourner le commentaire spécifique
                } else {
                    echo json_encode(['message' => 'Commentaire non trouvé']);
                }
            } else {
                // Récupérer tous les commentaires en attente
                $comments = $this->commentModel->getPendingComments();
                if ($comments) {
                    echo json_encode($comments);
                } else {
                    echo json_encode(['message' => 'Aucun commentaire en attente']);
                }
            }
        } catch (Exception $e) {
            echo json_encode(['error' => 'Erreur lors de la récupération des données']);
        }
    }


    public function showAdminHome()
    {
        $comments = $this->commentModel->getPendingComments(); // Récupération des commentaires
        require_once __DIR__ . '/../views/home_admin.php'; // Charger la vue avec les données
    }

    public function approveComment()
    {
        try {
            // Vérifier si l'ID du commentaire est présent dans l'URL
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $commentId = $_GET['id'];

                // Log pour voir l'ID du commentaire
                error_log("Tentative d'approbation du commentaire ID: " . $commentId);

                // Appeler la méthode pour mettre à jour le statut du commentaire
                $this->commentModel->updateCommentStatus($commentId, 'approved');

                // Récupérer l'email de l'utilisateur
                $userEmail = $this->commentModel->getUserEmailByCommentId($commentId);

                if ($userEmail) {
                    // Log pour voir l'email de l'utilisateur
                    error_log("Email de l'utilisateur: " . $userEmail);

                    // Créer une instance de PHPMailer
                    $mail = new PHPMailer(true);

                    try {
                        // Paramètres du serveur
                        $mail->isSMTP();
                        $mail->Host = $_ENV['MAIL_HOST'];  // Choisis ton serveur SMTP
                        $mail->SMTPAuth = true;
                        $mail->Username = $_ENV['MAIL_USERNAME']; // Ton email SMTP
                        $mail->Password = $_ENV['MAIL_PASSWORD']; // mot de passe SMTP
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = $_ENV['MAIL_PORT'];

                        // Destinataire et expéditeur
                        $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
                        $mail->addAddress($userEmail);

                        // Contenu de l'email
                        $mail->isHTML(true);
                        $mail->CharSet = 'UTF-8';
                        $mail->Subject = 'Votre commentaire a été approuvé';
                        $mail->Body = 'Bonjour,<br><br>Nous avons approuvé votre commentaire sur notre site. 
                    Merci de votre participation !<br><br>Cordialement,<br>L\'équipe';

                        // Envoi de l'email
                        $mail->send();

                        // Log succès
                        error_log("Email envoyé avec succès à : " . $userEmail);
                    } catch (Exception $e) {
                        // Log erreur PHPMailer
                        error_log("Erreur lors de l'envoi de l'email: " . $mail->ErrorInfo);
                    }
                } else {
                    // Log si aucun email trouvé pour l'utilisateur
                    error_log("Aucun email trouvé pour le commentaire ID: " . $commentId);
                }

                // Réponse à l'utilisateur
                echo json_encode(['message' => 'Commentaire approuvé']);
                http_response_code(200);
            } else {
                // Log si l'ID est invalide
                error_log("ID du commentaire invalide ou non spécifié");
                echo json_encode(['error' => 'ID du commentaire invalide']);
                http_response_code(400);
            }
        } catch (Exception $e) {
            // Log d'erreur générique
            error_log("Erreur lors de l'approbation du commentaire: " . $e->getMessage());
            echo json_encode(['error' => 'Erreur lors de l\'approbation du commentaire']);
            http_response_code(500);
        }
    }



    public function deleteComment()
    {
        try {
            // Vérifier si l'ID du commentaire est bien passé dans l'URL
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $commentId = $_GET['id'];
                // Appeler la méthode pour supprimer le commentaire
                $this->commentModel->deleteComment($commentId);
                // Récupérer l'email de l'utilisateur
                $userEmail = $this->commentModel->getUserEmailByCommentId($commentId);

                if ($userEmail) {
                    // Créer une instance de PHPMailer
                    $mail = new PHPMailer(true);

                    try {
                        // Paramètres du serveur
                        $mail->isSMTP();
                        $mail->Host = 'smtp.example.com';  // Choisis ton serveur SMTP
                        $mail->SMTPAuth = true;
                        $mail->Username = 'tonemail@example.com'; // Ton email SMTP
                        $mail->Password = 'tonmotdepasse'; // Ton mot de passe SMTP
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        // Destinataire et expéditeur
                        $mail->setFrom('no-reply@tonsite.com', 'Ton Site');
                        $mail->addAddress($userEmail);

                        // Contenu de l'email
                        $mail->isHTML(true);
                        $mail->Subject = 'Votre commentaire a été supprimé';
                        $mail->Body = 'Bonjour,<br><br>Nous sommes désolés, mais nous avons supprimé votre commentaire sur notre site.<br><br>Cordialement,<br>L\'équipe';

                        // Envoi de l'email
                        $mail->send();
                    } catch (Exception $e) {
                        echo "Erreur lors de l'envoi de l'email: {$mail->ErrorInfo}";
                    }
                }

                echo json_encode(['message' => 'Commentaire supprimé']);
                http_response_code(200);
            } else {
                echo json_encode(['error' => 'ID du commentaire invalide']);
                http_response_code(400);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => 'Erreur lors de la suppression du commentaire']);
            http_response_code(500);
        }
    }





}
