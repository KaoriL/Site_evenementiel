<?php
$maxComments = 4;
$commentCount = count($comments);
$shownComments = array_slice($comments, 0, $maxComments);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaires</title>
    <link rel="stylesheet" href="public/assets/css/index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="public/assets/css/commentaire.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php require_once 'header.php'; // Inclure ton header ?>
    <div class="fond-comment">
        <section class="center">
            <div class="commentaire">
                <div class="media-gallery">
                    <h2>Galerie des avis</h2>
                    <div class="gallery-grid"></div>
                    <div class="gallery-more" onclick="showFullGallery()"></div>
                </div>
                <!-- Modal pour l'affichage plein écran -->
                <div class="media-modal" onclick="closeFullScreen()">
                    <span class="close-modal">&times;</span>
                    <div class="media-content"></div>
                </div>
                <div class="avis">
                    <div>
                        <div class="rating-summary">
                            <h2>Avis récents</h2>
                            <p id="averageRating"></p>
                            <p id="commentCount"></p>
                            <div id="stars" class="stars">
                                <!-- Les étoiles seront insérées ici via JavaScript -->
                            </div>
                        </div>
                    </div>
                    <div class="comment_recent">
                        <?php foreach ($shownComments as $comment): ?>
                            <div class="comment-box">
                                <div class="en-tete">
                                    <h4><?= htmlspecialchars($comment['username']) ?></h4>
                                    <p class="stars">
                                        <?= str_repeat('★', $comment['rating']) . str_repeat('☆', 5 - $comment['rating']) ?>
                                    </p>
                                </div>
                                <div class="centre_comment">
                                    <p class="comment-text"
                                        data-full-text="<?= nl2br(htmlspecialchars($comment['comment'])) ?>">
                                        <!-- Le JS va gérer l'affichage du "Voir plus" ici -->
                                    </p>
                                    <div class="comment-media">
                                        <?php
                                        $media = [];
                                        if (!empty($comment['image'])) {
                                            $media[] = '<img  src="' . htmlspecialchars($comment['image']) . '" alt="Image du commentaire">';
                                        }
                                        if (!empty($comment['video'])) {
                                            $media[] = '<video controls><source src="' . htmlspecialchars($comment['video']) . '" type="video/mp4"></video>';
                                        }
                                        if (count($media) > 1): ?>
                                            <div class="media-preview" onclick="openLightbox(this)">
                                                <?= $media[0] ?>
                                                <div class="overlay">+<?= count($media) - 1 ?></div>
                                            </div>
                                            <div class="lightbox">
                                                <div class="lightbox-content">
                                                    <span class="close" onclick="closeLightbox()">&times;</span>
                                                    <div class="media-container">
                                                        <?php foreach ($media as $item): ?>
                                                            <div class="media-item"><?= $item ?></div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <button class="prev" onclick="prevMedia()">&#10094;</button>
                                                    <button class="next" onclick="nextMedia()">&#10095;</button>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="media-single"><?= implode('', $media) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <small class="comment-date">Posté le <?= $comment['created_at'] ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if ($commentCount > $maxComments): ?>
                        <button class="show-more-comments" onclick="openPopup()">Voir les autres commentaires</button>
                    <?php endif; ?>
                    <div id="commentPopup" class="popup">
                        <div class="popup-content">
                            <span class="close-popup" onclick="closePopup()">&times;</span>
                            <h2>Tous les commentaires</h2>
                            <div class="all-comments">
                                <?php foreach ($comments as $comment): ?>
                                    <div class="comment-box">
                                        <div class="en-tete">
                                            <h4><?= htmlspecialchars($comment['username']) ?></h4>
                                            <p class="stars">
                                                <?= str_repeat('★', $comment['rating']) . str_repeat('☆', 5 - $comment['rating']) ?>
                                            </p>
                                        </div>
                                        <div class="centre_comment">
                                            <p class="comment-text"
                                                data-full-text="<?= nl2br(htmlspecialchars($comment['comment'])) ?>">
                                                <!-- Le JS va gérer l'affichage du "Voir plus" ici -->
                                            </p>
                                        </div>
                                        <small class="comment-date">Posté le <?= $comment['created_at'] ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a href="index.php?action=addComment">Ajoutcomment</a>
        </section>

    </div>
    <?php require_once 'footer.php'; // Inclure ton footer ?>


</body>
<script>
    function openPopup() {
        document.getElementById("commentPopup").style.display = "flex";
    }
    function closePopup() {
        document.getElementById("commentPopup").style.display = "none";
    }
    // Fonction pour afficher les étoiles remplies en fonction de la note moyenne
    const comments = <?php echo json_encode($comments); ?>;
    function displayRating(comments) {
        const totalComments = comments.length;
        const totalRating = comments.reduce((sum, comment) => sum + comment.rating, 0);
        const averageRating = totalComments > 0 ? (totalRating / totalComments).toFixed(1) : 0;

        // Mise à jour du nombre de commentaires et de la note moyenne
        document.getElementById('commentCount').textContent = `${totalComments} avis`;
        document.getElementById('averageRating').textContent = `${averageRating} |`;

        // Affichage des étoiles
        const starsDiv = document.getElementById('stars');
        starsDiv.innerHTML = ''; // Réinitialise les étoiles

        // Ajouter les étoiles remplies selon la note
        for (let i = 1; i <= 5; i++) {
            const star = document.createElement('span');
            star.classList.add('star');
            if (i <= averageRating) {
                star.classList.add('filled');
            } else {
                star.classList.add('empty');
            }
            star.textContent = '★'; // Caractère étoile
            starsDiv.appendChild(star);
        }
    }


    // Affichage de la note et des commentaires
    displayRating(comments);

    let currentMediaIndex = 0;

    function openLightbox(element) {
        document.querySelector('.lightbox').style.display = 'flex';
        currentMediaIndex = 0;
        showMedia(currentMediaIndex);
    }

    function closeLightbox() {
        document.querySelector('.lightbox').style.display = 'none';
    }

    function showMedia(index) {
        let mediaItems = document.querySelectorAll('.media-item');
        mediaItems.forEach((item, i) => {
            item.style.display = i === index ? 'block' : 'none';
        });
    }

    function nextMedia() {
        let mediaItems = document.querySelectorAll('.media-item');
        currentMediaIndex = (currentMediaIndex + 1) % mediaItems.length;
        showMedia(currentMediaIndex);
    }

    function prevMedia() {
        let mediaItems = document.querySelectorAll('.media-item');
        currentMediaIndex = (currentMediaIndex - 1 + mediaItems.length) % mediaItems.length;
        showMedia(currentMediaIndex);
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".comment-text").forEach(comment => {
            const maxLength = 100; // Nombre de caractères avant de tronquer
            const fullText = comment.dataset.fullText.trim(); // Texte complet

            if (fullText.length > maxLength) {
                const truncatedText = fullText.substring(0, maxLength);
                comment.innerHTML = `${truncatedText}<span class="see-more" onclick="toggleComment(this)">... Voir plus</span>`;
                comment.dataset.fullText = fullText;
                comment.dataset.truncatedText = truncatedText;
            } else {
                comment.innerHTML = fullText;
            }
        });
    });

    function toggleComment(span) {
        const comment = span.parentElement;
        if (comment.classList.contains("expanded")) {
            comment.innerHTML = `${comment.dataset.truncatedText}<span class="see-more" onclick="toggleComment(this)">... Voir plus</span>`;
            comment.classList.remove("expanded");
        } else {
            comment.innerHTML = `${comment.dataset.fullText}<span class="see-more" onclick="toggleComment(this)"> Voir moins</span>`;
            comment.classList.add("expanded");
        }
    }




</script>

</html>