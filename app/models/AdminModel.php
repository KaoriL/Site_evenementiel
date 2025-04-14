<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class AdminModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }


    public function getPrestationsParDate()
    {
        try {
            $sql = "SELECT d.id AS disponibilite_id, d.date_disponible, d.horaire, d.est_reserve,
                           p.id AS prestation_id, p.nom, p.prenom, p.telephone, p.email AS prestation_email, p.service, p.lieu, p.date_evenement,
                           pm.id AS prestation_mariage_id, 
                           pm.nom_marie, pm.prenom_marie, pm.telephone_marie, pm.email_marie,
                           pm.nom_mariee, pm.prenom_mariee, pm.telephone_mariee, pm.email_mariee, pm.service AS service_mariage, pm.lieu AS lieu_mariage, pm.date_evenement AS date_evenement_mariage,
                           pm.origine_marie, pm.origine_mariee
                    FROM disponibilites d
                    LEFT JOIN prestations p ON d.id = p.disponibilite_id
                    LEFT JOIN prestations_mariage pm ON d.id = pm.disponibilite_id";

            $stmt = $this->db->query($sql);

            if (!$stmt) {
                throw new Exception("Erreur lors de l'exécution de la requête SQL.");
            }

            $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($resultats === false) {
                throw new Exception("Erreur lors de la récupération des résultats.");
            }

            return $resultats;  // ✅ On retourne les résultats

        } catch (Exception $e) {
            error_log("Erreur dans DisponibiliteModel::getPrestationsParDate : " . $e->getMessage());
            return []; // ✅ Retourne un tableau vide au lieu de "void"
        }
    }


    public function homeAdmin()
    {
        // Page d'accueil pour l'admin
        require __DIR__ . '/../views/home_admin.php';
    }
    // Méthode pour mettre à jour la date et l'heure de l'événement

    public function updateEvent($event_id, $new_date, $new_time, $type)
    {
        error_log("La fonction updateEvent est appelée avec : event_id=$event_id, new_date=$new_date, new_time=$new_time, type=$type");
        try {
            // Choisir la bonne table selon le type
            $table = ($type === 'mariage') ? 'prestations_mariage' : 'prestations';
            error_log("le type est : $table");

            $sql_update = "UPDATE $table SET rdv_date = :new_date, rdv_horaire = :new_time WHERE disponibilite_id = :event_id";
            $stmt_update = $this->db->prepare($sql_update);
            $stmt_update->bindParam(':new_date', $new_date);
            $stmt_update->bindParam(':new_time', $new_time);
            $stmt_update->bindParam(':event_id', $event_id);
            $stmt_update->execute();

            // / Vérification du succès de la mise à jour dans prestations
            if ($stmt_update->rowCount() > 0) {
                error_log("La mise à jour de l'événement dans $table a été effectuée avec succès.");

                // Récupérer le disponibilite_id de la table prestations (ou prestations_mariage)
                $sql_get_disponibilite = "SELECT disponibilite_id FROM $table WHERE disponibilite_id = :event_id";
                $stmt_get_disponibilite = $this->db->prepare($sql_get_disponibilite);
                $stmt_get_disponibilite->bindParam(':event_id', $event_id);
                $stmt_get_disponibilite->execute();
                $disponibilite = $stmt_get_disponibilite->fetch(PDO::FETCH_ASSOC);

                if ($disponibilite) {
                    $disponibilite_id = $disponibilite['disponibilite_id'];

                    // Mettre à jour la table disponibilites avec les nouvelles données
                    $sql_update_disponibilite = "UPDATE disponibilites SET date_disponible = :new_date, horaire = :new_time WHERE id = :disponibilite_id";
                    $stmt_update_disponibilite = $this->db->prepare($sql_update_disponibilite);
                    $stmt_update_disponibilite->bindParam(':new_date', $new_date);
                    $stmt_update_disponibilite->bindParam(':new_time', $new_time);
                    $stmt_update_disponibilite->bindParam(':disponibilite_id', $disponibilite_id);
                    $stmt_update_disponibilite->execute();

                    error_log("La mise à jour de la disponibilité a été effectuée avec succès.");
                } else {
                    error_log("Aucun créneau de disponibilité trouvé pour l'événement ID: $event_id");
                }

            } else {
                error_log("Aucune ligne n'a été mise à jour dans la table $table.");
            }
            return true;

        } catch (Exception $e) {
            error_log('Error in updateEvent: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteEvent($disponibilite_id, $type)
    {
        if (empty($disponibilite_id) || empty($type)) {
            error_log("Erreur : disponibilite_id ou type manquant. disponibilite_id: " . print_r($disponibilite_id, true) . ", type: " . print_r($type, true));
            return false;
        }

        $table = ($type === 'mariage') ? 'prestations_mariage' : 'prestations';
        error_log("Suppression de l'événement lié à la disponibilité ID $disponibilite_id dans la table $table");

        try {
            // Récupérer l'ID de l'événement lié à la disponibilité
            $stmt = $this->db->prepare("SELECT id FROM $table WHERE disponibilite_id = :disponibilite_id");
            $stmt->execute(['disponibilite_id' => $disponibilite_id]);
            $event = $stmt->fetch();

            if (!$event) {
                error_log("Erreur : Aucun événement trouvé avec disponibilite_id $disponibilite_id dans la table $table");
                return false;
            }

            $event_id = $event['id'];

            // Supprimer l'événement
            $stmt = $this->db->prepare("DELETE FROM $table WHERE id = :event_id");
            $success = $stmt->execute(['event_id' => $event_id]);

            if (!$success) {
                error_log("Erreur SQL : Échec de la suppression de l'événement ID $event_id dans la table $table");
                return false;
            }

            // Marquer la disponibilité comme libre
            $stmt = $this->db->prepare("UPDATE disponibilites SET est_reserve = 0 WHERE id = :disponibilite_id");
            $success = $stmt->execute(['disponibilite_id' => $disponibilite_id]);

            if (!$success) {
                error_log("Erreur SQL : Échec de la mise à jour de la disponibilité ID " . $disponibilite_id);
                return false;
            }

            error_log("Succès : Événement ID $event_id supprimé et disponibilité ID " . $disponibilite_id . " libérée");
            return true;
        } catch (PDOException $e) {
            error_log("Exception SQL : " . $e->getMessage());
            return false;
        }
    }


    // Méthode pour envoyer un email de mise à jour
    public function sendEmailUpdate($email, $new_date, $new_time)
    {
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
            $mail->addAddress($email);
            // Contenu de l'email
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->ContentType = 'text/html';
            $mail->Subject = 'Votre Rendez-vous a été déplacé';
            $new_date = date('d-m-Y', strtotime($new_date));
            $new_time = date('H:i', strtotime($new_time));

            $mail->AddEmbeddedImage('public/assets/image/img-accueil.jpeg', 'bannerImage', 'img-accueil.jpeg');
            $mail->Body = '
  


             <html> 
    <head> 
    <style> 
    body { 
    font-family: Arial, sans-serif; 
    background:rgb(235, 228, 215); 
    color:rgb(0, 0, 0); 
    margin: 0; padding: 0; 
    } 
    .container { 
    background: #F9F7F3; 
    max-width: 600px;
    margin: 20px auto; 
    border-radius: 8px; 
    overflow: hidden; 
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
    } 
    .header { 
    background: #000; 
    color: white; 
    text-align: center; 
    padding: 15px; 
    font-size: 22px; 
    font-weight: bold; 
    } 
    .banner img { 
    width: 100%; 
    height: 250px; 
    object-fit:cover; 
    } 
     .content { 
     padding: 20px; 
     text-align: center; 
    } 
    .button { 
    display: inline-block;
    background: #2563eb; 
    color: white; 
    padding: 10px 20px; 
    border-radius: 5px; 
    text-decoration: none; 
    font-weight: bold; 
    } 
    a{ 
    text-decoration: none; 
    color:rgb(255, 255, 255); 
    } 
    .info-box { 
    background:rgb(233, 228, 219); 
    padding: 15px; 
    border-radius: 5px; 
    text-align: left; 
    margin-top: 20px; 
    } 
    .footer { 
    text-align: center; 
    padding: 15px; 
    font-size: 12px; 
    background:rgb(39, 39, 39); 
    color:rgb(255, 255, 255); 
    } 
    h2{
    font-size:24px;
    font-family:Arial bold;
    color:rgb(0, 0, 0);
    } 
    </style> 
    </head> 
    <body> 
    <div class="container"> 
    <div class="header"> 
    DEEJAY13 
    </div> 
    <div class="banner">
    <img src="cid:bannerImage" alt="Bannière">
    </div> <div class="content">Bonjour,<br><br>Votre rendez-vous a été déplacé à la nouvelle date et heure : <br><br>' . '<h2>' . $new_date . ' à ' . $new_time . '</h2>'
                . '</p> <p>Vous pouvez nous contacter au 06 98 29 26 78</p> 
     </div> 
     <div class="footer"> © ' . date('Y') . ' DeeJay 13. Tous droits réservés. </div> </div> </body> </html>';
            // Envoi de l'email
            if (!$mail->send()) {
                error_log('Erreur envoi mail : ' . $mail->ErrorInfo);
                return false;
            }
            return true;
            // Log succès

        } catch (Exception $e) {
            // Log erreur PHPMailer
            error_log("Erreur lors de l'envoi de l'email: " . $mail->ErrorInfo);
        }

    }

    public function sendEmailUpdateDelete($email)
    {
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
            $mail->addAddress($email);
            // Contenu de l'email
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->ContentType = 'text/html';
            $mail->Subject = 'Votre Rendez-vous a été Supprimé';

            $mail->AddEmbeddedImage('public/assets/image/img-accueil.jpeg', 'bannerImage', 'img-accueil.jpeg');
            $mail->Body = '
  


             <html> 
    <head> 
    <style> 
    body { 
    font-family: Arial, sans-serif; 
    background:rgb(235, 228, 215); 
    color:rgb(0, 0, 0); 
    margin: 0; padding: 0; 
    } 
    .container { 
    background: #F9F7F3; 
    max-width: 600px;
    margin: 20px auto; 
    border-radius: 8px; 
    overflow: hidden; 
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
    } 
    .header { 
    background: #000; 
    color: white; 
    text-align: center; 
    padding: 15px; 
    font-size: 22px; 
    font-weight: bold; 
    } 
    .banner img { 
    width: 100%; 
    height: 250px; 
    object-fit:cover; 
    } 
     .content { 
     padding: 20px; 
     text-align: center; 
    } 
    .button { 
    display: inline-block;
    background: #2563eb; 
    color: white; 
    padding: 10px 20px; 
    border-radius: 5px; 
    text-decoration: none; 
    font-weight: bold; 
    } 
    a{ 
    text-decoration: none; 
    color:rgb(255, 255, 255); 
    } 
    .info-box { 
    background:rgb(233, 228, 219); 
    padding: 15px; 
    border-radius: 5px; 
    text-align: left; 
    margin-top: 20px; 
    } 
    .footer { 
    text-align: center; 
    padding: 15px; 
    font-size: 12px; 
    background:rgb(39, 39, 39); 
    color:rgb(255, 255, 255); 
    } 
    h2{
    font-size:24px;
    font-family:Arial bold;
    color:rgb(0, 0, 0);
    } 
    </style> 
    </head> 
    <body> 
    <div class="container"> 
    <div class="header"> 
    DEEJAY13 
    </div> 
    <div class="banner">
    <img src="cid:bannerImage" alt="Bannière">
    </div> <div class="content">Bonjour,<br><br>Votre rendez-vous a été Supprimer <br><br>'
                . '</p> <p>Vous pouvez nous contacter au 06 98 29 26 78</p> 
     </div> 
     <div class="footer"> © ' . date('Y') . ' DeeJay 13. Tous droits réservés. </div> </div> </body> </html>';
            // Envoi de l'email
            if (!$mail->send()) {
                error_log('Erreur envoi mail : ' . $mail->ErrorInfo);
                return false;
            }
            return true;
            // Log succès

        } catch (Exception $e) {
            // Log erreur PHPMailer
            error_log("Erreur lors de l'envoi de l'email: " . $mail->ErrorInfo);
        }
    }
}





?>