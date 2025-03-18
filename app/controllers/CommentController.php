<?php

require_once __DIR__ . '/../models/CommentModel.php';


class CommentController
{
    private $commentModel;

    public function __construct($db)
    {
        $this->commentModel = new CommentModel($db); // Assure-toi que le modèle est bien instancié

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

            // Handling image upload
            $imagePath = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->commentModel->handleFileUpload(
                    $_FILES['image'], 
                    ['image/jpeg', 'image/png', 'image/gif'], 
                    2 * 1024 * 1024, 'public/uploads/images/');
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
            $result = $this->commentModel->submitComment($user_id, $rating, $comment, $imagePath, $videoPath, $ip_address);

            if ($result) {
                header("Location: index.php?action=commentaires");
                exit;
            } else {
                echo "Une erreur est survenue lors de l'ajout du commentaire.";
            }



        }
        require_once __DIR__ . '/../views/AjoutComment.php';
    }

}

?>