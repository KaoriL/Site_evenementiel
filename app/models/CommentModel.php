<?php
class CommentModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllComments()
    {
        $query = "SELECT c.*, u.username FROM comments c 
                  JOIN users u ON c.user_id = u.id
                  ORDER BY c.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fonction pour insérer un commentaire avec image et vidéo (si présents)
    public function submitComment($user_id, $rating, $comment, $imagePath = null, $videoPath = null, $ip_address, )
    {
        // Préparer la requête SQL pour insérer un commentaire
        $stmt = $this->db->prepare("INSERT INTO comments (user_id, rating, comment, image, video, ip_address) 
                                    VALUES (?, ?, ?, ?, ?, ?)");

        // Exécuter la requête avec les paramètres
        $stmt->execute([$user_id, $rating, $comment, $imagePath, $videoPath, $ip_address]);

        // Vérifier si l'insertion a réussi
        if ($stmt->rowCount() > 0) {
            return true; // Le commentaire a bien été inséré
        } else {
            return false; // L'insertion a échoué
        }
    }

    public function handleFileUpload($file, $allowedTypes, $maxSize, $uploadDir)
    {
        // Vérifie si le fichier est vide
        if (empty($file['name'])) {
            return '';  // Aucun fichier, on renvoie une chaîne vide
        }
    
        // Vérifier la taille du fichier
        if ($file['size'] > $maxSize) {
            return 'Le fichier dépasse la taille maximale autorisée.';
        }
    
        // Vérifier le type MIME
        $mimeType = mime_content_type($file['tmp_name']);
        if (!in_array($mimeType, $allowedTypes)) {
            return 'Le type de fichier n\'est pas autorisé.';
        }
    
        // Vérifier s'il y a une erreur d'upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return 'Une erreur est survenue lors du téléchargement du fichier.';
        }
    
        // Générer un nom de fichier unique pour éviter les conflits
        $uniqueName = uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    
        // Déplacer le fichier vers le répertoire de destination
        $destination = $uploadDir . $uniqueName;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $destination;  // Renvoie le chemin du fichier
        } else {
            return 'Le fichier n\'a pas pu être déplacé.';
        }
    }
    

}
?>