<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="public/assets/css/index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="public/assets/css/accueil.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php require_once 'header.php'; // Inclure ton header ?>

    <?php
    // // Vérifie si l'utilisateur est connecté
// if (!isset($_SESSION['user_id'])) {
//     $message = "Erreur : utilisateur non connecté.";
//     // Redirige vers la page de login
// 
// }
// // Si connecté, afficher les infos de l'utilisateur
// echo "Bonjour " . $_SESSION['username'];
// ?>
    <section class="banniere">
        <img src="public/assets/image/img-accueil.jpeg" alt="Image en noir et blanc" class="bw-image">
        <div>
            <h1>
                <span class="left">Avec Deejay13</span>
                VIVEZ DES INSTANTS UNIQUE
                <span class="right">Pour des souvenirs éternels</span>
            </h1>
            <button>
                <a href="index.php?action=presta">Prestations</a>
            </button>

        </div>
    </section>

    <section class="centre">
        <div class="ruban">
            <p>GALA • CONFÉRENCES • ANNIVERSAIRE • MARIAGE • SÉMINAIRE • SOIRÉE PRIVÉE • AFTER WORK </p>
        </div>

        <div id="essentiel" class="essentiel">
            <div class="description" id="description">
                <h2>PACK CONFORT</h2>
                <h3>PARCE QUE VOUS LE MERITEZ</h3>
                <p>Un pack pensé pour votre bien-être
                    et votre tranquillité, avec tout ce dont
                    vous avez besoin pour briller sans stress.
                </p>

                <button> <a href="index.php?action=devis&type=mariage">
                    Je veux ce moment de confort</a></button>
            </div>
            <div class="img-essentiel" id="img-essentiel">

                <img class="img-border" 
                src="public/assets/image/img-confort-accueil.jpg" alt="">
                <img class="img-door" 
                src="public/assets/image/img-confort-accueil2.jpg" alt="">
            </div>
        </div>

        <div class="gala">
            <div class="img-gala">
                <img src="public/assets/image/img-gala-accueil.png" alt="">
            </div>
            <div class="description">
                <h2>UN GALA <br>D'EXCEPTION</h2>
                <h3>CRÉEZ UNE SOIRÉE INOUBLIABLE</h3>
                <p>Confiez-nous votre vision, vos envies et vos idées les plus audacieuses,
                    et laissez-nous orchestrer chaque détail pour transformer votre événement
                    en un véritable spectacle magique, qui restera gravé dans les mémoires.
                </p>
                <div class="btn-r">
                    <button> <a href="index.php?action=devis">Créer mon gala inoubliable</a></button>
                </div>

            </div>
        </div>

        <div class="moment">
            <div class="description">
                <h2>MOMENTS PARTAGÉS</h2>
                <h3>DES MOMENTS MÉMORABLES POUR DES CLIENTS QUI NOUS ONT
                    CONFIÉ LEURS RÊVES. DÉCOUVREZ QUELQUES INSTANTS CAPTURÉS DE NOS COLLABORATIONS RÉUSSIES.</h3>
            </div>
            <div class="moment_photo" id="moment-photo">
                <img src="public/assets/image/dj_event_gospel.png" alt="">
                <img src="public/assets/image/Mariee_dj_gospel_afro.png" alt="">
                <img src="public/assets/image/femme_mariage_africain_gospel_dj.png" alt="">
                <div class="btn-r">
                    <p>FAITES PARTIE DE
                        NOS SUCCESS STORIES</p>
                    <div class="line"></div>
                    <button> <a href="index.php?action=devis">Confiez nous votre évènement</a></button>
                </div>
            </div>
        </div>

        <div id="temoignage" class="temoignage">
            <h2>TÉMOIGNAGES</h2>
            <div id="latest-comments-container">
            </div>
            <div><a href="index.php?action=commentaires" class="btn">Voir tous les commentaires</a></div>
        </div>
        <div class="nombre">
            <p><span>12</span>Années d’expérience</p>
            <p><span>+500</span>Prestations</p>
            <p><span>15</span>Prestations dans le monde</p>
            <p><span>600</span>Clients satisfaits</p>

        </div>
    </section>
    <?php require_once 'footer.php'; // Inclure ton footer ?>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        fetch("index.php?action=getLatestComments")
            .then(response => response.json())
            .then(comments => {
                let container = document.getElementById("latest-comments-container");
                container.innerHTML = ""; // Vide le conteneur

                if (comments.length === 0) {
                    container.innerHTML = "<p>Aucun commentaire pour le moment.</p>";
                    return;
                }

                comments.forEach(comment => {
                    let commentDiv = document.createElement("div");
                    commentDiv.classList.add("card");

                    // Générer le média (image ou vidéo)
                    let mediaHtml = "";
                    if (comment.image) {
                        mediaHtml = `<img src="${comment.image}" alt="Image du commentaire" class="comment-media">`;
                    } else if (comment.video) {
                        mediaHtml = `<video controls class="comment-media"><source src="${comment.video}" type="video/mp4"></video>`;
                    }

                    commentDiv.innerHTML = `
                    <h4>${comment.username}</h4>
                     ${mediaHtml} <!-- Affiche l'image ou la vidéo si elle existe -->
                    
                    <p>${comment.comment.substring(0, 100)}...</p> <!-- Tronque le texte -->
                    <p class="stars">${"★".repeat(comment.rating) + "☆".repeat(5 - comment.rating)}</p>
                    
                `;

                    container.appendChild(commentDiv);
                });
            })
            .catch(error => {
                console.error("Erreur lors du chargement des commentaires :", error);
                document.getElementById("latest-comments-container").innerHTML = "<p>Erreur de chargement des commentaires.</p>";
            });
    });

    function handleResponsiveEssentiel() {
    const description = document.getElementById('description');
    const imgEssentiel = document.getElementById('img-essentiel');
    const h3 = description.querySelector('h3'); // Sélectionne le <h3>

    if (window.innerWidth <= 768) {
        // Déplacer img-essentiel dans description après le <h3>
        if (!description.contains(imgEssentiel)) {
            description.insertBefore(imgEssentiel, h3.nextSibling);
        }
    } else {
        // Remettre img-essentiel à sa position initiale
        const essentiel = document.getElementById('essentiel');
        if (essentiel && !essentiel.contains(imgEssentiel)) {
            essentiel.appendChild(imgEssentiel);
        }
    }
}

// Ajouter un écouteur pour détecter le redimensionnement de la fenêtre
window.addEventListener('resize', handleResponsiveEssentiel);

// Appeler la fonction au chargement de la page
document.addEventListener('DOMContentLoaded', handleResponsiveEssentiel);

function handleResponsiveImages() {
    const momentPhoto = document.getElementById('moment-photo');
    const images = momentPhoto.querySelectorAll('img'); // Sélectionne toutes les images

    if (window.innerWidth <= 768) {
        // Affiche uniquement les deux premières images
        images.forEach((img, index) => {
            img.style.display = index < 2 ? 'block' : 'none';
        });
    } else {
        // Affiche toutes les images en mode non responsive
        images.forEach(img => {
            img.style.display = 'block';
        });
    }
}

// Ajouter un écouteur pour détecter le redimensionnement de la fenêtre
window.addEventListener('resize', handleResponsiveImages);

// Appeler la fonction au chargement de la page
document.addEventListener('DOMContentLoaded', handleResponsiveImages);

</script>

</html>