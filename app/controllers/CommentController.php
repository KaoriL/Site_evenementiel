<?php

require_once __DIR__ . '/../models/CommentModel.php';


class CommentController
{
    private $commentModel;

    public function __construct($db)
    {
        $this->commentModel = new CommentModel($db); // Assure-toi que le modèle est bien instancié

    }


public function getLatestComments()
{
    try {
        // Log avant de récupérer les commentaires
        error_log("Tentative de récupération des derniers commentaires");

        // Récupérer les commentaires avec une limite de 8
        $comments = $this->commentModel->getCommentsWithLimit(8, 0);

        // Vérifier si les commentaires sont vides
        if (empty($comments)) {
            error_log("Aucun commentaire trouvé");
        }

        // Retourner les commentaires au format JSON
        header('Content-Type: application/json');
        echo json_encode($comments);

        // Log après avoir retourné la réponse
        error_log("Commentaires récupérés avec succès");
    } catch (Exception $e) {
        // En cas d'erreur, enregistrer l'erreur dans le log
        error_log("Erreur lors de la récupération des commentaires : " . $e->getMessage());
        echo json_encode(['error' => 'Erreur lors de la récupération des commentaires']);
    }

    exit;
}




    public function showComments()
    {
        $comments = $this->commentModel->getAllComments();
        require_once __DIR__ . '/../views/Commentaire.php';
    }
    private function uploadFile($file)
    {
        $uploadDir = __DIR__ . '/../../public/uploads/';
        $uploadFile = $uploadDir . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $uploadFile);

        return basename($file['name']);
    }

    public function submitComment()
    {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            echo "Vous devez être connecté pour ajouter un commentaire.";
            exit;
        }
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $comment = htmlspecialchars($_POST['comment']);
            $rating = $_POST['rating'];
            $user_id = $_SESSION['user_id'];
            $prestation = htmlspecialchars($_POST['prestation']);
            if ($prestation === "autre" && !empty($_POST['autre_prestation'])) {
                $prestation = htmlspecialchars($_POST['autre_prestation']);
            }
            // Handling image upload
            $imagePath = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->commentModel->handleFileUpload(
                    $_FILES['image'],
                    ['image/jpeg', 'image/png', 'image/gif'],
                    2 * 1024 * 1024,
                    'public/uploads/images/'
                );
            }

            // Traiter la vidéo
            $videoPath = '';  // Par défaut, pas de vidéo
            if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
                $videoPath = $this->commentModel->handleFileUpload(
                    $_FILES['video'],
                    ['video/mp4', 'video/webm', 'video/ogg'],
                    10 * 1024 * 1024, // Taille max 10MB
                    'public/uploads/videos/'
                );
            }


            $ip_address = $_SERVER['REMOTE_ADDR']; // L'adresse IP

            // Insérer le commentaire dans la base de données via le modèle
            $result = $this->commentModel->submitComment($user_id, $rating, $comment, $prestation, $imagePath, $videoPath, $ip_address);

            if ($result) {
                $_SESSION['flash_source'] = "commentaire";
                $_SESSION['flash_message'] = "Votre commentaire a bien été soumis, il est en cours de validation.";
                header("Location: index.php?action=confirmation");
                exit;
            } else {
                echo "Une erreur est survenue lors de l'ajout du commentaire.";
            }



        }
        require_once __DIR__ . '/../views/AjoutComment.php';
    }

}

?>