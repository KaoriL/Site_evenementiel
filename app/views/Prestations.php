<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prestations</title>
    <link rel="stylesheet" href="public/assets/css/index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="public/assets/css/presta.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php require_once 'header.php'; // Inclure ton header ?>
    <!--<a href="index.php?action=devis">Devis</a>
    <a href="index.php?action=devis_mariage&type=mariage">mariage</a>
    <a href="index.php?action=mariage">presta</a>-->

    <section class="banniere w100">
        <img src="public/assets/image/couple_africain_pesta.jpeg" alt="Image en noir et blanc" class="bw-image cover w100 absolute">
        <div class="w100 h100 relative flex center align-center wrap column">
            <h1>
                NOS PRESTATIONS
            </h1>
        </div>
    </section>

    <section class="centre">
        <div class="ruban">
            <p>GALA • CONFÉRENCES • ANNIVERSAIRE • MARIAGE • SÉMINAIRE • SOIRÉE PRIVÉE • AFTER WORK </p>
        </div>

        <p class="entrer">Experts en sonorisation, éclairage, DJ, effets spéciaux et animation, nous créons des
            ambiances uniques pour chaque occasion.</p>

        <div id="essentiel" class="essentiel">
            <div id="description" class="description">
                <h2>MARIAGE</h2>
                <h3>CÉLÉBREZ L’AMOUR AVEC ÉLÉGANCE ET PASSION</h3>
                <p>Plusieurs prestations pour sublimer votre mariage : musique, lumière, et maître de cérémonie, chaque
                    service pensé pour répondre aux besoins de chacun.
                </p>
                <div class="btn">
                    <button> <a href="index.php?action=mariage">En savoir plus</a></button>

                </div>
            </div>
            <div id="img-essentiel" class="img-essentiel">
                <img class="img-border" src="public/assets/image/img-presta-mariage.jpeg"
                    alt="couple_africains_mariage_lumieres">
            </div>
        </div>

        <div id="gala" class="gala">
            <div class="img-essentiel">
                <img class="img-left" src="public/assets/image/img-presta-left.jpeg" alt="dj_mariage_platine">
                <img class="img-right" src="public/assets/image/img-presta-right.jpeg" alt="mariage_evenementiel_dj">
            </div>
            <div class="description-gala">
                <h2>GALA</h2>
                <h3>NOUS CRÉONS DES AMBIANCES INOUBLIABLES ALLIANT RAFFINEMENT ET SPECTACLE POUR VOS ÉVÉNEMENTS.</h3>
                <div class="btn">
                    <button> <a href="index.php?action=devis">Devis</a></button>
                </div>
            </div>
        </div>

        <div id="soiree" class="soiree">
            <div class="img-fond-soiree">
                <div class="fond-soiree"></div>
                <img class="img-soiree" src="public/assets/image/img-presta-soiree.jpeg" alt="couple_africain_mariage">
            </div>


            <div class="description-soiree">
                <div class="dessus">
                    <h2>SOIRÉE PRIVÉE</h2>
                    <h3>DES SOIRÉES PRIVÉES SUR MESURE, ALLIANT ÉLÉGANCE, AMBIANCE UNIQUE ET MOMENTS INOUBLIABLES.</h3>
                    <div class="btn">
                        <button> <a href="index.php?action=devis">Devis</a></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="autre_presta">
            <div class="description">
                <h2>AUTRES PRESTATIONS</h2>
                <h3>DES PRESTATIONS SUR MESURE, ADAPTÉES À CHAQUE OCCASION ET CHAQUE EXIGENCE</h3>
            </div>
            <div class="autre_presta_index">
                <div class="autre"><img src="public/assets/image/anniversaire_dj.jpeg" alt="">
                    <h6>Anniversaire</h6>
                </div>
                <div class="autre"><img src="public/assets/image/conference.jpeg" alt="">
                    <h6>Conférence</h6>
                </div>
                <div class="autre"><img src="public/assets/image/after_work_dj.jpeg" alt="">
                    <h6>After-work</h6>
                </div>
            </div>
            <div class="btn">
                <button> <a href="index.php?action=devis">Devis</a></button>
            </div>
        </div>

        <div class="perso">
     <div class="img-perso">
         <img src="public/assets/image/dj_evenements_platines_dj_gospel.jpeg" alt="">
     </div>
     <div class="description">
         <h2>DEMANDE <br>
         PERSONNALISÉE</h2>
         <h3>DES SOLUTIONS ADAPTÉES À VOS BESOINS <br>POUR UN ÉVÉNEMENT À VOTRE IMAGE</h3>
         
         <div class="btn">
             <button> <a href="index.php?action=devis">Devis</a></button>
         </div>
     </div>
 </div>
    </section>
    <?php require_once 'footer.php'; // Inclure ton footer ?>
</body>
<script>
    // Fonction pour déplacer la div img-essentiel
function handleResponsiveLayout() {
    const description = document.getElementById('description');
    const imgEssentiel = document.getElementById('img-essentiel');

    if (window.innerWidth <= 768) {
        // Déplacer img-essentiel dans description
        if (!description.contains(imgEssentiel)) {
            description.appendChild(imgEssentiel);
        }
    } else {
        // Remettre img-essentiel à sa position initiale si nécessaire
        const parent = document.querySelector('.essentiel');
        if (parent && !parent.contains(imgEssentiel)) {
            parent.appendChild(imgEssentiel);
        }
    }
}

// Ajouter un écouteur pour détecter le redimensionnement de la fenêtre
window.addEventListener('resize', handleResponsiveLayout);

// Appeler la fonction au chargement de la page
document.addEventListener('DOMContentLoaded', handleResponsiveLayout);

function addButtonsToImages() {
    const autres = document.querySelectorAll('.autre'); // Sélectionne toutes les divs .autre

    if (window.innerWidth <= 768) {
        autres.forEach((autre) => {
            // Vérifie si le bouton n'existe pas déjà
            if (!autre.querySelector('.btn')) {
                const btn = document.createElement('div'); // Crée une div pour le bouton
                btn.classList.add('btn'); // Ajoute la classe btn
                btn.innerHTML = `<button><a href="index.php?action=devis">Devis</a></button>`; // Ajoute le contenu du bouton
                autre.appendChild(btn); // Ajoute le bouton à la div .autre
            }
        });
    } else {
        // Supprime les boutons si la fenêtre est plus grande que 768px
        autres.forEach((autre) => {
            const btn = autre.querySelector('.btn');
            if (btn) {
                autre.removeChild(btn); // Supprime le bouton
            }
        });
    }
}

// Ajouter un écouteur pour détecter le redimensionnement de la fenêtre
window.addEventListener('resize', addButtonsToImages);

// Appeler la fonction au chargement de la page
document.addEventListener('DOMContentLoaded', addButtonsToImages);
</script>

</html>