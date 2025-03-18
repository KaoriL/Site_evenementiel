<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout de commentaire</title>
</head>
<body>
<?php
?>
<form  action="index.php?action=addComment" method="POST" enctype="multipart/form-data">
    <label for="rating">Note (1 à 5 étoiles) :</label>
    <select name="rating" id="rating">
        <option value="1">1 étoile</option>
        <option value="2">2 étoiles</option>
        <option value="3">3 étoiles</option>
        <option value="4">4 étoiles</option>
        <option value="5">5 étoiles</option>
    </select>
    <br>
    <label for="comment">Commentaire :</label>
    <textarea name="comment" id="comment" rows="4" required></textarea>
    <br>
    <label for="image">Image (optionnelle) :</label>
    <input type="file" name="image" accept="image/*">
    <br>
    <label for="video">Vidéo (optionnelle) :</label>
    <input type="file" name="video" accept="video/*">
    <br>
    <button type="submit">Envoyer</button>
</form>
</body>
</html>