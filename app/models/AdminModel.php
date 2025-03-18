<?php
class AdminModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getPrestationsParDate()
    {
        $sql = "SELECT d.id AS disponibilite_id, d.date_disponible, d.horaire, d.est_reserve,
                p.id AS prestation_id, pm.id AS prestation_mariage_id,
                 p.email AS prestation_email, pm.email_mariee AS mariage_email
         FROM disponibilites d
         LEFT JOIN prestations p ON d.id = p.disponibilite_id
         LEFT JOIN prestations_mariage pm ON d.id = pm.disponibilite_id";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function deleteEvent($event_id, $type)
    {
        // Récupérer l'ID de la disponibilité
        $table = ($type === 'mariage') ? 'prestations_mariage' : 'prestations';

        $stmt = $this->db->prepare("SELECT disponibilite_id FROM $table WHERE id = :event_id");
        $stmt->execute(['event_id' => $event_id]);
        $event = $stmt->fetch();

        if (!$event) {
            return false; // Événement non trouvé
        }

        // Supprimer l'événement
        $stmt = $this->db->prepare("DELETE FROM $table WHERE id = :event_id");
        $stmt->execute(['event_id' => $event_id]);

        // Marquer la disponibilité comme libre
        $stmt = $this->db->prepare("UPDATE disponibilites SET est_reserve = 0 WHERE id = :disponibilite_id");
        $stmt->execute(['disponibilite_id' => $event['disponibilite_id']]);

        return true;
    }


    // Méthode pour envoyer un email de mise à jour
    public function sendEmailUpdate($email, $new_date, $new_time)
    {
        $subject = "Votre rendez-vous a été déplacé";
        $message = "Bonjour, \n\nVotre rendez-vous a été déplacé à la nouvelle date et heure : $new_date à $new_time.\n\nCordialement, \nL'équipe d'événement.";
        $headers = "From: contact@tonsite.com\r\n";
        $headers .= "Reply-To: contact@tonsite.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        return mail($email, $subject, $message, $headers);
    }
}





?>