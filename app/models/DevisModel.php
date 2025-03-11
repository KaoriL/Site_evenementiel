<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class DevisModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }


    // public function fetchDisponibilites()
    // {
    //     $sql = "SELECT id, date_disponible, horaire, est_reserve 
    //         FROM disponibilites 
    //         WHERE est_reserve = 1 
    //         AND DATE(date_disponible) > CURDATE() 
    //         AND WEEKDAY(date_disponible) NOT IN (5,6) 
    //         AND horaire BETWEEN '10:00:00' AND '21:00:00'
    //         ORDER BY date_disponible, horaire";
    //     $stmt = $this->db->query($sql);
    //     $disponibilites = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //     // Convertir les dates au format Y-m-d 
    //     foreach ($disponibilites as &$dispo) {
    //         $dispo['date_disponible'] = date('Y-m-d', strtotime($dispo['date_disponible']));
    //     }

    //     return $disponibilites;
    // }


    public function Disponibilites()
    {
        $joursOuvrables = [1, 2, 3, 4, 5]; // Lundi à Vendredi (0 = Dimanche, 6 = Samedi)
        $heureDebut = new DateTime('10:00:00', new DateTimeZone('Europe/Paris'));
        $heureFin = new DateTime('22:00:00', new DateTimeZone('Europe/Paris'));
        $dateDebut = new DateTime('now +1 day', new DateTimeZone('Europe/Paris'));
        $dureeCreneau = new DateInterval('PT1H'); // Créneaux d'1 heure

        $disponibilites = [];

        // Date de départ = demain
        $dateDebut = new DateTime('now +1 day');

        //echo "Date demain : " . $dateDebut->format('Y-m-d') . "\n"; // Affiche demain

        // Générer les créneaux pour les 30 prochains jours
        for ($i = 0; $i < 365; $i++) {

            $date = (clone $dateDebut)->modify("+$i day");

            // Vérifier si c'est un jour ouvrable
            if (in_array($date->format('N'), $joursOuvrables)) {
                $heure = clone $heureDebut;
                while ($heure < $heureFin) {
                    $disponibilites[] = [
                        'date_disponible' => $date->format('Y-m-d'),
                        'horaire' => $heure->format('H:i:s'),
                        'est_reserve' => 0 // Par défaut, tout est libre
                    ];
                    $heure->add($dureeCreneau);
                }
            }
        }

        // Récupérer les créneaux réservés depuis la BDD
        $sql = "SELECT date_disponible, horaire FROM disponibilites WHERE est_reserve = 1";
        $stmt = $this->db->query($sql);
        $reserves = $stmt->fetchAll(PDO::FETCH_ASSOC);


        // Transformer en tableau associatif pour un accès rapide
        $reservations = [];
        foreach ($reserves as $res) {
            $reservations[$res['date_disponible']][$res['horaire']] = true;
        }

        // Marquer les créneaux réservés
        foreach ($disponibilites as &$creneau) {
            if (isset($reservations[$creneau['date_disponible']][$creneau['horaire']])) {
                $creneau['est_reserve'] = 1;
            }
        }

        return $disponibilites;
    }



    public function saveDevisStandard($data)
    {

        // Initialisation des variables avec les valeurs ou NULL si vides
        $date_evenement = empty($data['date_evenement']) ? null : $data['date_evenement'];
        $lieu = empty($data['lieu']) ? null : $data['lieu'];
        $message = empty($data['message']) ? null : $data['message'];

        // Construction de la requête avec des paramètres conditionnels
        $query = "INSERT INTO prestations (user_id, nom, prenom, email, telephone, 
                                      date_evenement, rdv_date, rdv_horaire, 
                                      service, lieu, message, disponibilite_id)
              VALUES (:user_id, :nom, :prenom, :email, :telephone, 
                      :date_evenement, :rdv_date, :rdv_horaire, 
                      :service, :lieu, :message, :disponibilite_id)";

        $stmt = $this->db->prepare($query);

        // Lier les paramètres, avec des valeurs par défaut si non renseignées
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':prenom', $data['prenom']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telephone', $data['telephone']);

        // Si la date de l'événement est vide, on n'enregistre pas cette donnée
        $stmt->bindParam(':date_evenement', $date_evenement);
        $stmt->bindParam(':rdv_date', $data['rdv_date']);
        $stmt->bindParam(':rdv_horaire', $data['rdv_horaire']);
        $stmt->bindParam(':service', $data['service']);

        // Idem pour le lieu et message
        $stmt->bindParam(':lieu', $lieu);
        $stmt->bindParam(':message', $message);

        // Disponibilité
        $stmt->bindParam(':disponibilite_id', $data['disponibilite_id']);

        if ($stmt->execute()) {
            // Mise à jour de 'est_reserve' dans la table 'disponibilites'
            $updateQuery = "UPDATE disponibilites SET est_reserve = 1 WHERE id = :id";
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->bindParam(':id', $data['disponibilite_id']);
            $updateStmt->execute();

            return true;
        }
        return false;
    }

    public function saveDevisMariage($data)
    {
        // Initialisation des variables avec les valeurs ou NULL si vides
        $date_evenement = empty($data['date_evenement']) ? null : $data['date_evenement'];
        $lieu = empty($data['lieu']) ? null : $data['lieu'];
        $message = empty($data['message']) ? null : $data['message'];
        // L'âge peut être laissé NULL si non renseigné
        $age_marie = empty($data['age_marie']) ? null : $data['age_marie'];
        $age_mariee = empty($data['age_mariee']) ? null : $data['age_mariee'];

        // Vérification des origines, elles sont obligatoires
        if (empty($data['origine_marie']) || empty($data['origine_mariee'])) {
            // Si une origine est vide, on arrête et on retourne false
            throw new Exception("L'origine du marié et de la mariée doivent être renseignées.");
        }

        // Construction de la requête avec des paramètres conditionnels
        $query = "INSERT INTO prestations_mariage 
                  (user_id, nom_marie, prenom_marie, email_marie, telephone_marie, 
                   nom_mariee, prenom_mariee, email_mariee, telephone_mariee, 
                   age_marie, age_mariee, origine_marie, origine_mariee, 
                   date_evenement, lieu, message, rdv_date, rdv_horaire, service, disponibilite_id)
                  VALUES 
                  (:user_id, :nom_marie, :prenom_marie, :email_marie, :telephone_marie, 
                   :nom_mariee, :prenom_mariee, :email_mariee, :telephone_mariee, 
                   :age_marie, :age_mariee, :origine_marie, :origine_mariee, 
                   :date_evenement, :lieu, :message, :rdv_date, :rdv_horaire, :service, :disponibilite_id)";

        // Préparation de la requête PDO
        $stmt = $this->db->prepare($query);

        // Lier les paramètres
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':nom_marie', $data['nom_marie']);
        $stmt->bindParam(':prenom_marie', $data['prenom_marie']);
        $stmt->bindParam(':email_marie', $data['email_marie']);
        $stmt->bindParam(':telephone_marie', $data['telephone_marie']);
        $stmt->bindParam(':nom_mariee', $data['nom_mariee']);
        $stmt->bindParam(':prenom_mariee', $data['prenom_mariee']);
        $stmt->bindParam(':email_mariee', $data['email_mariee']);
        $stmt->bindParam(':telephone_mariee', $data['telephone_mariee']);
        $stmt->bindParam(':age_marie', $age_marie);  // Lier l'âge du marié (peut être NULL)
        $stmt->bindParam(':age_mariee', $age_mariee);  // Lier l'âge de la mariée (peut être NULL)
        $stmt->bindParam(':origine_marie', $data['origine_marie']);  // Lier l'origine du marié
        $stmt->bindParam(':origine_mariee', $data['origine_mariee']);  // Lier l'origine de la mariée
        $stmt->bindParam(':date_evenement', $date_evenement);
        $stmt->bindParam(':lieu', $lieu);
        $stmt->bindParam(':message', $message);
        // Lier les nouvelles informations
        $stmt->bindParam(':rdv_date', $data['rdv_date']);
        $stmt->bindParam(':rdv_horaire', $data['rdv_horaire']);
        $stmt->bindParam(':service', $data['service']);
        $stmt->bindParam(':disponibilite_id', $data['disponibilite_id']);

        // Exécution de la requête
        if ($stmt->execute()) {
            // Mise à jour de 'est_reserve' dans la table 'disponibilites'
            $updateQuery = "UPDATE disponibilites SET est_reserve = 1 WHERE id = :id";
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->bindParam(':id', $data['disponibilite_id']);
            $updateStmt->execute();

            return true;
        }
        return false;
    }



    public function fetchRendezVous($user_id)
    {

        $sql = "
                SELECT id, user_id, rdv_date, rdv_horaire, message, service, date_evenement, 'standard' AS type 
                FROM prestations 
                WHERE user_id = :user_id
                UNION 
                SELECT id, user_id, rdv_date, rdv_horaire, message, service, date_evenement, 'mariage' AS type
                FROM prestations_mariage 
                WHERE user_id = :user_id";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function envoyerMailConfirmationMariage($data)
    {
        $mail = new PHPMailer(true);
        try {
            // Configurer le serveur SMTP 
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD']; // mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['MAIL_PORT'];
            $mail->SMTPDebug = 2; // Activer le debug (0 pour production)
            $mail->Debugoutput = 'error_log'; // Stocke les logs dans error_log

            $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
            $mail->addAddress($data['email_marie']); // Adresse du marié
            $mail->addAddress($data['email_mariee']); // Adresse de la mariée

            // Contenu 
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->ContentType = 'text/html';
            $mail->Subject = 'Confirmation de votre rendez-vous en ligne';

            $dateEvenement = date('d-m-Y', strtotime($data['date_evenement']));
            $rdvDate = date('d-m-Y', strtotime($data['rdv_date']));
            $rdvHoraire = date('H:i', strtotime($data['rdv_horaire']));

            $mail->AddEmbeddedImage('public/assets/image/img-accueil.jpeg', 'bannerImage', 'img-accueil.jpeg');
            $mail->Body =
                ' <html> 
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
        </style> 
        </head> 
        <body> 
        <div class="container"> 
        <div class="header"> 
        DEEJAY13 
        </div> 
        <div class="banner">
        <img src="cid:bannerImage" alt="Bannière">
        </div> <div class="content">
        <p>Bonjour <strong>' . htmlspecialchars($data['prenom_mariee']) . '&'
                . htmlspecialchars($data['prenom_marie'])
                . '</strong>,</p> <p>Nous vous confirmons votre rendez-vous en visio le :</p> <h2>'
                . $rdvDate
                . '</h2> <h3>'
                . $rdvHoraire
                . '</h3> <p>Votre lien Zoom pour le rendez-vous :</p> <a href="https://us02web.zoom.us/j/81806042119" class="button" style="color:white;">LIEN ZOOM</a>
         <div class="info-box">
         <p><strong>Récapitulatif de vos informations :</strong></p> 
         <p>Date de votre événement : ' . $dateEvenement . '</p> 
         <p>Lieu de votre événement : ' . htmlspecialchars($data['lieu'])
                . '</p> <p>Prestation : ' . htmlspecialchars($data['service'])
                . '</p> <p>Message : ' . nl2br(htmlspecialchars($data['message']))
                . '</p> </div> <p>Nous vous enverrons plus d&apos;informations prochainement.</p> 
         </div> 
         <div class="footer"> © ' . date('Y') . ' DeeJay 13. Tous droits réservés. </div> </div> </body> </html>';

            if (!$mail->send()) {
                error_log('Erreur envoi mail : ' . $mail->ErrorInfo);
                return false;
            }
            return true;


        } catch (Exception $e) {
            // Si une exception se produit, on log l'exception et on retourne false
            error_log('Exception PHPMailer : ' . $e->getMessage());
            return false;
        }
    }

    public function envoyerMailConfirmation($data)
    { // 
        // echo "Début de la fonction<br>";
        $mail = new PHPMailer(true);
        try {
            // Configurer le serveur SMTP 
            $mail->isSMTP();

            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD']; // mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['MAIL_PORT'];

            $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);

            $mail->addAddress($data['email']);
            // Contenu 
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->ContentType = 'text/html';
            $mail->Subject = 'Confirmation de votre rendez-vous';
            // Formatage des dates au format DD-MM-YYYY
            $dateEvenement = date('d-m-Y', strtotime($data['date_evenement']));
            $rdvDate = date('d-m-Y', strtotime($data['rdv_date']));
            $rdvHoraire = date('H:i', strtotime($data['rdv_horaire'])); // Formatage de l'horaire

            $mail->AddEmbeddedImage('public/assets/image/img-accueil.jpeg', 'bannerImage', 'img-accueil.jpeg');
            $mail->Body =
                ' <html> 
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
        </style> 
        </head> 
        <body> 
        <div class="container"> 
        <div class="header"> 
        DEEJAY13 
        </div> 
        <div class="banner">
        <img src="cid:bannerImage" alt="Bannière">
        </div> <div class="content">
        <p>Bonjour <strong>' . htmlspecialchars($data['prenom'])
                . '</strong>,</p> <p>Nous vous confirmons votre rendez-vous en visio le :</p> <h2>'
                . $rdvDate
                . '</h2> <h3>'
                . $rdvHoraire
                . '</h3> <p>Votre lien Zoom pour le rendez-vous :</p> <a href="https://us02web.zoom.us/j/81806042119" class="button" style="color:white;">LIEN ZOOM</a>
         <div class="info-box">
         <p><strong>Récapitulatif de vos informations :</strong></p> 
         <p>Date de votre événement : ' . $dateEvenement . '</p> 
         <p>Lieu de votre événement : ' . htmlspecialchars($data['lieu'])
                . '</p> <p>Prestation : ' . htmlspecialchars($data['service'])
                . '</p> <p>Message : ' . nl2br(htmlspecialchars($data['message']))
                . '</p> </div> <p>Nous vous enverrons plus d&apos;informations prochainement.</p> 
         </div> 
         <div class="footer"> © ' . date('Y') . ' DeeJay 13. Tous droits réservés. </div> </div> </body> </html>';

            if (!$mail->send()) {
                error_log('Erreur envoi mail : ' . $mail->ErrorInfo);
                return false;
            }
            return true;
        } catch (Exception $e) {
            // Si une exception se produit, on log l'exception et on retourne false
            error_log('Exception PHPMailer : ' . $e->getMessage());
            return false;
        }
    }
    /////// ENVOIE NOTIFICATION TELEGRAM //////////////////// 

    public function sendTelegramNotification($message)
    {
        //Token et Chat ID du bot Telegram 
        $token = $_ENV['TOKEN'];
        $chat_id = $_ENV['CHAT_ID'];
        // URL de l'API Telegram 
        $url = "https://api.telegram.org/bot$token/sendMessage";
        // Paramètre pour l'envoie du message 
        $data = ['chat_id' => $chat_id, 'text' => $message,];
        //Utilisation de cURL pour envoyer la requête POST à l'API Telegram 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }
}
?>