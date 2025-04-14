<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout de commentaire</title>
    <link rel="stylesheet" href="public/assets/css/index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="public/assets/css/ajout_comment.css?v=<?php echo time(); ?>">
</head>

<body class="fullv flex center align-center bg-black">
    <?php if (isset($_SESSION['upload_errors'])): ?>
        <div class="error-messages">
            <?php if (!empty($_SESSION['upload_errors']['imageError'])): ?>
                <p class="error"><?= $_SESSION['upload_errors']['imageError'] ?></p>
            <?php endif; ?>
            <?php if (!empty($_SESSION['upload_errors']['videoError'])): ?>
                <p class="error"><?= $_SESSION['upload_errors']['videoError'] ?></p>
            <?php endif; ?>
        </div>
        <?php unset($_SESSION['upload_errors']); ?>
    <?php endif; ?>


    <form class="flex column w50 bg-white bor-radius10 p-20 gap align-center m-w500" action="index.php?action=addComment" method="POST"
        enctype="multipart/form-data">
        <h3 class="text-center">Un avis sur votre événement ?<br> Partagez-le avec nous !</h3>
        <div class="flex column align-center w100">
            <label for="rating">Note sur 5</label>
            <!-- From Uiverse.io by Ratinax -->
            <div class="radio-input">
                <input name="rating" value="5" type="radio" class="star s5" />
                <input name="rating" value="4" type="radio" class="star s4" />
                <input name="rating" value="3" type="radio" class="star s3" />
                <input name="rating" value="2" type="radio" class="star s2" />
                <input name="rating" value="1" type="radio" class="star s1" />

            </div>
        </div>
        <br>
        <label for="prestation">Prestation choisi :</label>
        <select class="w100 border-box p-10 c-grey bor-radius10" name="prestation" id="prestation" required>
            <option value="" disabled selected>Choisissez une prestation</option>
            <option value="Pack Essentiel">Pack Essensiel</option>
            <option value="Pack Confort">Pack Confort</option>
            <option value="Pack Privilège">Pack Privilège</option>
            <option value="Pack Royal Dream">Pack Royal Dream</option>
            <option value="Anniversaire">Anniversaire</option>
            <option value="Soirée privée">Soirée privée</option>
            <option value="Gala">Gala</option>
            <option value="autre">Autre</option>
        </select>
        <br>

        <label for="comment">Commentaire :</label>
        <textarea class="w100 bor-radius10" name="comment" id="comment" rows="4" required></textarea>
        <br>
        <label for="image">Image (optionnelle) :</label>
        <input type="file" name="image" accept="image/*">
        <p id="image-error" class="error-message" style="color: red;"></p>
        <br>
        <label for="video">Vidéo (optionnelle) :</label>
        <input type="file" name="video" accept="video/*">
        <p id="video-error" class="error-message" style="color: red;"></p>
        <br>
        <button class=""type="submit">Envoyer</button>
    </form>

</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const imageInput = document.querySelector('input[name="image"]');
        const videoInput = document.querySelector('input[name="video"]');

        const maxImageSize = 2 * 1024 * 1024; // 2MB
        const maxVideoSize = 10 * 1024 * 1024; // 10MB

        function validateFile(input, maxSize, errorElementId) {
            input.addEventListener("change", function () {
                const file = input.files[0];
                const errorElement = document.getElementById(errorElementId);
                if (file) {
                    if (file.size > maxSize) {
                        errorElement.textContent = `Le fichier sélectionné est trop grand. Taille maximale : ${maxSize / 1024 / 1024} MB./mo`;
                        input.value = ""; // Réinitialise le champ pour empêcher l'envoi du fichier
                    } else {
                        errorElement.textContent = ""; // Efface l'erreur si le fichier est valide
                    }
                }
            });
        }

        validateFile(imageInput, maxImageSize, "image-error");
        validateFile(videoInput, maxVideoSize, "video-error");
    });
</script>

</html>