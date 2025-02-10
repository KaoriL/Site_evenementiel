<?php
class DevisModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function fetchDisponibilites() {
        $sql = "SELECT date_disponible, horaire, est_reserve FROM disponibilites WHERE est_reserve = 0 ORDER BY date_disponible, horaire";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fonction pour enregistrer le devis dans la base de données
    public function saveDevis($data) {
        $query = "INSERT INTO prestations (nom, prenom, email, telephone, date_evenement, rdv_date, rdv_horaire, service, lieu, message) 
                  VALUES (:nom, :prenom, :email, :telephone, :date_evenement, :rdv_date,:rdv_horaire, :service, :lieu, :message)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':prenom', $data['prenom']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telephone', $data['telephone']);
        $stmt->bindParam(':date_evenement', $data['date_evenement']);
        $stmt->bindParam(':rdv_date', $data['rdv_date']);
        $stmt->bindParam(':rdv_horaire', $data['rdv_horaire']);
        $stmt->bindParam(':service', $data['service']);
        $stmt->bindParam(':lieu', $data['lieu']);
        $stmt->bindParam(':message', $data['message']);
        
        return $stmt->execute(); // Retourne true si l'insertion est réussie
    }
}
?>
