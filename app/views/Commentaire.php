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
            <h1>AVIS</h1>
            <div class="commentaire flex column gap">
            <h3 class="text-left">Galerie des avis</h3>
                <div class="comment-gallery">
                    <div class="gallery-grid flex gap ">
                        <?php
                        $totalMedia = 0;
                        foreach ($comments as $comment) {
                            if (!empty($comment['image']) || !empty($comment['video'])) {
                                $totalMedia++;
                            }
                        }

                        $count = 0;
                        foreach ($comments as $index => $comment):
                            if ($count >= 4)
                                break; // Afficher seulement 5 éléments
                            if (!empty($comment['image']) || !empty($comment['video'])):
                                $count++;
                                ?>
                                <div class="gallery-item <?= ($count === 5 && $totalMedia > 5) ? 'gallery-more' : '' ?>"
                                    data-index="<?= $index ?>">
                                    <?php if (!empty($comment['image'])): ?>
                                        <img src="<?= $comment['image'] ?>" alt="Image" class="open-media"
                                            data-index="<?= $index ?>">
                                    <?php else: ?>
                                        <video controls class="open-media" data-index="<?= $index ?>">
                                            <source src="<?= $comment['video'] ?>" type="video/mp4">
                                        </video>
                                    <?php endif; ?>
                                    <?php if ($count === 4 && $totalMedia > 4): ?>
                                        <div class="overlay open-gallery"><p>+<?= $totalMedia - 4 ?></p></div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; endforeach; ?>
                    </div>
                </div>
                <!-- ✅ Popup pour afficher un média seul -->
                <div class="single-media-modal">
                    <span class="close-single-modal" onclick="closeFullScreen()">&times;</span>
                    <div class="media-content"></div>
                    <button class="prev" onclick="prevMedial()">&#10094;</button>
                    <button class="next" onclick="nextMedial()">&#10095;</button>
                </div>

                <!-- ✅ Popup pour la galerie complète -->
                <div class="media-modal">
                    <span class="close-modal" onclick="closeFullGallery()">&times;</span>
                    <div class="media-content grid"></div>
                </div>

                <div class="avis">
                    <div>
                        <div class="rating-summary">
                            <h3 class="text-left">Avis récents</h3>
                            <p id="averageRating"></p>
                            <p id="commentCount"></p>
                            <div id="stars" class="stars">
                                <!-- Les étoiles seront insérées ici via JavaScript -->
                            </div>
                        </div>
                    </div>

                    <!------------------------------------------------- COMMENTAIRES RÉCENTS----------------------------------------->
                    <div class="comment_recent" id="comment-recent">
                        <?php foreach ($shownComments as $comment): ?>
                        <div class="comment-box">
                            <div class="en-tete flex row gap border-box align-center">
                                <h4>
                                    <?= htmlspecialchars($comment['username']) ?>
                                </h4>
                                <p class="stars">
                                    <?= str_repeat('★', $comment['rating']) . str_repeat('☆', 5 - $comment['rating']) ?>
                                </p>
                            </div>
                            <h5 class="text-left "><?= htmlspecialchars($comment['prestation']) ?></h5> 
                            <div class="centre_comment flex row ">
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

                    <!------------------------------------------------- POP UP DE TOUS LES COMMENTAIRES----------------------------------------->
                    <div id="commentPopup" class="popup">
                        <div class="popup-content">
                            <span class="close-popup" onclick="closePopup()">&times;</span>
                            <h3 class="left">Tous les commentaires</h3>
                            <div class="all-comments">
                                <?php foreach ($comments as $comment): ?>
                                <div class="comment-box">
                                    <div class="en-tete flex row gap border-box align-center">
                                        <h4>
                                            <?= htmlspecialchars($comment['username']) ?>
                                        </h4>
                                        <p class="stars">
                                            <?= str_repeat('★', $comment['rating']) . str_repeat('☆', 5 - $comment['rating']) ?>
                                        </p>
                                    </div>
                                    <h5><?= htmlspecialchars($comment['prestation']) ?></h5> 
                                    <div class="centre_comment flex row">
                                        <p class="comment-text"
                                            data-full-text="<?= nl2br(htmlspecialchars($comment['comment'])) ?>">
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
                                                <div class="overlay">+
                                                    <?= count($media) - 1 ?>
                                                </div>
                                            </div>
                                            <div class="lightbox">
                                                <div class="lightbox-content">
                                                    <span class="close" onclick="closeLightbox()">&times;</span>
                                                    <div class="media-container">
                                                        <?php foreach ($media as $item): ?>
                                                        <div class="media-item">
                                                            <?= $item ?>
                                                        </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <button class="prev" onclick="prevMedia()">&#10094;</button>
                                                    <button class="next" onclick="nextMedia()">&#10095;</button>
                                                </div>
                                            </div>
                                            <?php else: ?>
                                            <div class="media-single">
                                                <?= implode('', $media) ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                    <small class="comment-date">Posté le
                                        <?= $comment['created_at'] ?>
                                    </small>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <a href="index.php?action=addComment">Ajoutcomment</a>
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
        let lightbox = document.querySelector('.lightbox');
        let mediaItems = document.querySelectorAll('.media-item');

        if (mediaItems.length === 0) {
            console.error("Aucun média trouvé !");
            return;
        }

        lightbox.style.display = 'flex';
        currentMediaIndex = 0;
        showMedia(currentMediaIndex);
    }

    function closeLightbox() {
        document.querySelector('.lightbox').style.display = 'none';
    }

    function showMedia(index) {
        let mediaItems = document.querySelectorAll('.media-item');

        if (mediaItems.length === 0) {
            console.error("Erreur : aucun média disponible !");
            return;
        }

        // Vérification que l'index est valide
        if (index < 0) {
            index = mediaItems.length - 1;
        } else if (index >= mediaItems.length) {
            index = 0;
        }

        currentMediaIndex = index;

        console.log(`Affichage du média ${currentMediaIndex} / ${mediaItems.length - 1}`);

        mediaItems.forEach((item, i) => {
            item.style.display = (i === index) ? 'block' : 'none';
        });
    }

    function nextMedia() {
        let mediaItems = document.querySelectorAll('.media-item');
        if (mediaItems.length === 0) return;

        currentMediaIndex = (currentMediaIndex + 1) % mediaItems.length;
        showMedia(currentMediaIndex);
    }

    function prevMedia() {
        let mediaItems = document.querySelectorAll('.media-item');
        if (mediaItems.length === 0) return;

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

    document.addEventListener("DOMContentLoaded", function () {
        let galleryItems = document.querySelectorAll(".open-media");
        let galleryMore = document.querySelector(".open-gallery");
        let currentMediaIndex = 0;


        // ✅ Ouvrir un média en fullscreen
        galleryItems.forEach((item, index) => {
            item.addEventListener("click", function () {
                openFullScreen(parseInt(this.dataset.index));
            });
        });

        // ✅ Ouvrir toute la galerie complète
        if (galleryMore) {
            galleryMore.addEventListener("click", openFullGallery);
        }

        function openFullScreen(index) {
            currentMediaIndex = index;
            let modalContent = document.querySelector(".single-media-modal .media-content");
            let modal = document.querySelector(".single-media-modal");

            if (!modalContent || !modal) {
                console.error("Erreur : .single-media-modal ou .media-content introuvable.");
                return;
            }

            modalContent.innerHTML = getMediaElement(index);
            modal.style.display = "flex";
        }

        function openFullGallery() {
            let fullGallery = document.querySelector(".media-modal .media-content");
            let modal = document.querySelector(".media-modal");
            fullGallery.innerHTML = "";

            comments.forEach((comment, index) => {

                // Si le commentaire contient une image, on crée un élément image
                if (comment.image) {
                    let imageElement = document.createElement("img");
                    imageElement.src = comment.image;
                    imageElement.classList.add("open-media");
                    imageElement.dataset.index = index;
                    imageElement.onclick = function () {
                        openFullScreen(index);
                    };
                    fullGallery.appendChild(imageElement);
                }

                // Si le commentaire contient une vidéo, on crée un élément vidéo
                if (comment.video) {
                    let videoElement = document.createElement("video");
                    videoElement.controls = true;
                    let source = document.createElement("source");
                    source.src = comment.video;
                    source.type = "video/mp4";
                    videoElement.appendChild(source);
                    videoElement.classList.add("open-media");
                    videoElement.dataset.index = index;
                    videoElement.onclick = function () {
                        openFullScreen(index);
                    };
                    fullGallery.appendChild(videoElement);
                }
            });

            modal.style.display = "flex";
        }
    });


    function getMediaElement(index) {
        let comment = comments[index];
        if (!comment) return "";

        let mediaHTML = "";

        // Si une image existe, on l'ajoute dans un conteneur dédié
        if (comment.image) {
            mediaHTML += `<div class="media-item image-item" >
                        <img src="${comment.image}" class="media-item">
                      </div>`;
        }

        // Si une vidéo existe, on l'ajoute dans un conteneur dédié
        if (comment.video) {
            mediaHTML += `<div class="media-item video-item">
                        <video controls class="media-item"><source src="${comment.video}" type="video/mp4"></video>
                      </div>`;
        }

        return mediaHTML;
    }


    // ✅ Fonction pour afficher le média actif
    function showMedial(index) {
        let modalContent = document.querySelector(".single-media-modal .media-content");
        modalContent.innerHTML = getMediaElement(index);
    }


    function closeFullGallery() {
        document.querySelector(".media-modal").style.display = "none";
    }
    function closeFullScreen() {
        document.querySelector(".single-media-modal").style.display = "none";
    }





    // ✅ Passer au média suivant
    function nextMedial() {
        let totalMedia = comments.length;
        currentMediaIndex = (currentMediaIndex + 1) % totalMedia;
        showMedial(currentMediaIndex);
    }
    // ✅ Revenir au média précédent
    function prevMedial() {
        let totalMedia = comments.length;
        currentMediaIndex = (currentMediaIndex - 1 + totalMedia) % totalMedia;
        showMedial(currentMediaIndex);
    }

    function handleResponsiveComments() {
    const commentContainer = document.getElementById('comment-recent');
    const comments = commentContainer.querySelectorAll('.comment-box');

    if (window.innerWidth <= 768) {
        // Affiche uniquement les 2 premiers commentaires
        comments.forEach((comment, index) => {
            comment.style.display = index < 2 ? 'block' : 'none';
        });
    } else {
        // Affiche tous les commentaires en mode non responsive
        comments.forEach(comment => {
            comment.style.display = 'block';
        });
    }
}

// Ajouter un écouteur pour détecter le redimensionnement de la fenêtre
window.addEventListener('resize', handleResponsiveComments);

// Appeler la fonction au chargement de la page
document.addEventListener('DOMContentLoaded', handleResponsiveComments);


</script>

</html>