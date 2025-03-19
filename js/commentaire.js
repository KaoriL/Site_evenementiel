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

document.addEventListener("DOMContentLoaded", function () {
    // Injecter le tableau PHP dans une variable JavaScript
    const allMedia = <?php echo json_encode($comments); ?>;
    console.log(allMedia);

    let galleryItems = document.querySelectorAll(".open-media");
    let galleryMore = document.querySelector(".open-gallery");

    function openFullGallery() {
let fullGallery = document.querySelector(".media-modal .media-content");
let modal = document.querySelector(".media-modal");

// Vérifier si l'élément fullGallery existe
if (!fullGallery || !modal) {
    console.error("Erreur : .media-modal ou .media-content introuvable.");
    return;
}

fullGallery.innerHTML = ""; // Nettoyer le contenu avant de l'ajouter

allMedia.forEach((item, index) => {
    let mediaElement;

    // Vérifier si l'item contient une image ou une vidéo
    if (item.image) {
        mediaElement = document.createElement("img");
        mediaElement.src = item.image;
    } else if (item.video) { // Vérification vidéo
        mediaElement = document.createElement("video");
        mediaElement.controls = true;
        let source = document.createElement("source");
        source.src = item.video;
        source.type = "video/mp4";
        mediaElement.appendChild(source);
    }

    // Vérifier que mediaElement est bien créé avant de l'ajouter
    if (mediaElement) {
        mediaElement.classList.add("open-media");
        mediaElement.dataset.index = index;
        mediaElement.dataset.type = item.image ? 'image' : 'video';
        mediaElement.dataset.src = item.image || item.video; // `item.image` ou `item.video`
        mediaElement.onclick = function () {
            showFullScreen(mediaElement.outerHTML, index);
        };

        fullGallery.appendChild(mediaElement);
    }
});

modal.style.display = "flex";
}


    function showFullScreen(mediaHTML, currentIndex) {
        let modalContent = document.querySelector(".single-media-modal .media-content");
        let modal = document.querySelector(".single-media-modal");

        if (!modalContent || !modal) {
            console.error("Erreur : .single-media-modal ou .media-content introuvable.");
            return;
        }

        modalContent.innerHTML = mediaHTML;
        modal.style.display = "flex";

        // Ajouter navigation entre les médias
        document.querySelector(".single-media-modal").setAttribute("data-current-index", currentIndex);

        // Ajouter des boutons de navigation
        const navControls = document.createElement("div");
        navControls.classList.add("nav-controls");
        navControls.innerHTML = `
        <button class="prev" onclick="prevMedia()">&#10094;</button>
        <button class="next" onclick="nextMedia()">&#10095;</button>
    `;
        modalContent.appendChild(navControls);
    }

    // Ajout du comportement d'affichage de la galerie
    galleryMore.addEventListener("click", openFullGallery);
});


