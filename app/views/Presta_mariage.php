<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mariage</title>
    <link rel="stylesheet" href="public/assets/css/index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="public/assets/css/mariage.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php require_once 'header.php'; // Inclure ton header ?>
    <section class="banniere">
        <img src="public/assets/image/img-mariage.jpeg" alt="Image en noir et blanc" class="bw-image">
        <div>
            <h1>
                MARIAGE
            </h1>
        </div>
    </section>

    <section class="centre gap50">
        <div class="ruban">
            <p>GALA • CONFÉRENCES • ANNIVERSAIRE • MARIAGE • SÉMINAIRE • SOIRÉE PRIVÉE • AFTER WORK </p>
        </div>

        <div class="flex column text-center align-center gap">
            <h2 class="c-white">NOS PACKS</h2>
            <p class="w50 c-white">UNE OFFRE ADAPTÉE À TOUS VOS ÉVÉNEMENTS</p>
            <div class="w75 bor-or p-10 bg-trans m-10">
                <ul class="text-left flex column gap">
                    <li><a id="scroll-link" class="c-beige" href="#essentiel">Pack Essentiel</a> - L’indispensable pour
                        une ambiance réussie </li>
                    <li><a id="scroll-link" class="c-beige" href="#confort">Pack Confort</a> - Un niveau supérieur pour
                        plus d'animations et d'effets</li>
                    <li><a id="scroll-link" class="c-beige" href="#privilege">Pack Privilège</a> - Une mise en scène
                        immersive et spéctaculaire</li>
                    <li><a id="scroll-link" class="c-beige" href="#royal">Pack Royal Dream</a> - L'expérience ultime
                        pour un événement inoubliable</li>
                </ul>
            </div>
        </div>

        <div id="essentiel" class="essentiel">
            <div class="description" id="description">
                <h2>PACK ESSENTIEL</h2>
                <h3 class="c-white w75">L'INCONTOURNABLE POUR UNE AMBIANCE RÉUSSIE</h3>
                <p class="c-white w75">L'essentiel : une soirée animée avec un DJ professionnel et un maître de
                    cérémonie pour une ambiance
                    garantie</p>
                <ul class="text-left">
                    <h4 class="c-white">Ce pack comprend:</h4>
                    <li><i class="fa-solid fa-circle-check"></i> Prestation <span>DJ</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> Pack <span> SONO PRO</span>(2 micro SF inclus)</li>
                    <li><i class="fa-solid fa-circle-check"></i> Maître de cérémonie/Animateur(Atalaku)</li>
                </ul>
            </div>
            <div class="img-essentiel" id="img-essentiel">
                <img class="img-border" src="public/assets/image/img-essentiel.jpeg" alt="">
            </div>
            <div class="btn flex column align-center">
                <button> <a href="index.php?action=devis&type=mariage">Devis</a></button>
                <h5 class="c-white m-10">Obtenez votre devis personalisé dès maintenant !</h5>
            </div>


        </div>



        <div id="confort" class="confort">
            <div class="img-confort">
                <img class="img-fond" src="public/assets/image/img-confort.jpeg" alt="">
            </div>
            <div class="description">
                <h2>PACK CONFORT</h2>
                <h3>UN NIVEAU SUPÉRIEUR POUR UNE AMBIANCE IMMERSIVE</h3>
                <p>Ajoutez une touche d’illumination et d’éclat à votre mariage pour une ambiance encore plus marquée.
                </p>

                <ul>
                    <h4>Ce pack comprend:</h4>
                    <li><i class="fa-solid fa-circle-check"></i> Prestation <span>DJ PRO</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> Maître de cérémonie/Animateur(Atalaku)</li>
                    <li><i class="fa-solid fa-circle-check"></i> Pack<span> SONO PRO</span>(2 micro SF inclus)</li>
                    <li><i class="fa-solid fa-circle-check"></i> Pack <span>LUMIÈRE</span>effet illumination garantie
                    </li>
                    <li><i class="fa-solid fa-circle-check"></i><span> Nuage au sol</span>(fumée lourde)</li>
                </ul>

                <div class="btn flex column align-center">
                    <h5>Obtenez votre devis personalisé dès maintenant !</h5>
                    <button> <a href="index.php?action=devis&type=mariage">Devis</a></button>
                    <h6>Options supplémentaires disponibles : Effets spéciaux (FX), éclairages avancés, feux
                        d’artifice...</h6>
                </div>
            </div>


        </div>

        <div id="privilege" class="privilege">
            <div id="privilege_desc" class="description">
                <div  id="left" class="left">
                    <h2>PACK PRIVILÈGE</h2>
                    <h3>ÉLÉGANCE ET MISE EN SCÈNE RAFFINÉE</h3>
                    <p>La définition d'un mariage d'exception garanti par la team de votre choix.
                    </p>

                    <ul>
                        <h4>Ce pack comprend:</h4>
                        <li><i class="fa-solid fa-circle-check"></i><span> Prestation DJ</span> de votre choix</li>
                        <li><i class="fa-solid fa-circle-check"></i> Maître de cérémonie/Animateur(Atalaku) de votre
                            choix
                        </li>
                        <li><i class="fa-solid fa-circle-check"></i> Pack<span> SONO PRO XXL</span> pour une qualité
                            sonore
                            exceptionnelle</li>
                        <li><i class="fa-solid fa-circle-check"></i> Pack <span>LUMIÈRE PRO XXL </span>effet
                            illumination
                            immersive
                        </li>
                        <li><i class="fa-solid fa-circle-check"></i><span> Nuage au sol </span>pour une ouverture de bal
                            féérique</li>
                        <li><i class="fa-solid fa-circle-check"></i><span> Grand écran Mur LED </span>pour des
                            projéctions
                            spéctaculaires</li>
                        <li><i class="fa-solid fa-circle-check"></i> Projecteur<span> LASER 3D </span>avec suivi précis
                            garantie</li>
                        <li><i class="fa-solid fa-circle-check"></i> <span> 4 Fontaines de jet d'artifices</span> inclus
                            pour sublimer votre ouverture de bal</li>
                        <li><i class="fa-solid fa-circle-check"></i> Pack<span> SONO ESSENTIEL</span> pour votre
                            cérémonie
                            nuptiale offert</li>
                    </ul>
                </div>
                <div id="img-privilege" class="img-privilege">
                    <img class="img-priv" src="public/assets/image/img-privilege1.jpeg" alt="">
                    <img class="img-priv2" src="public/assets/image/img-privilege2.jpeg" alt="">
                </div>
                <div class="btn flex column align-center">
                    <h5>Obtenez votre devis personalisé dès maintenant !</h5>
                    <button> <a href="index.php?action=devis&type=mariage">Devis</a></button>
                    <h6>Options supplémentaires disponibles : Effets spéciaux (FX), éclairages avancés, feux
                        d’artifice...</h6>
                </div>
            </div>
        </div>

        <div id="royal" class="royal">
            <div class="img-royal">
                <img class="img-fond-royal" src="public/assets/image/img-royal.jpeg" alt="">

            </div>
            <div class="description">

                <h2>PACK ROYAL DREAM</h2>
                <h3>L’EXPÉRIENCE ULTIME, UN MARIAGE FÉÉRIQUE</h3>
                <p>Le summum de l’élégance et du spectacle pour un mariage inoubliable, alliant musique live, mise en
                    scène grandiose et effets spéciaux prestigieux.
                </p>
                <ul>
                    <h4>Ce pack comprend:</h4>
                    <li><i class="fa-solid fa-circle-check"></i><span> BACK LINE</span> avec l'orchestre de votre choix
                    </li>
                    <li><i class="fa-solid fa-circle-check"></i> Pack<span> SONO PRO XXL</span> pour une qualité sonore
                        incomparable</li>
                    <li><i class="fa-solid fa-circle-check"></i> Maître de cérémonie/Animateur(Atalaku) de votre choix
                    </li>
                    <li><i class="fa-solid fa-circle-check"></i><span> DJ PRO</span> de votre choix</li>
                    <li><i class="fa-solid fa-circle-check"></i> Pack<span> LUMIÈRES PRO XXL</span> pour une
                        scénographie immersive</li>
                    <li><i class="fa-solid fa-circle-check"></i><span> Nuage au sol </span>pour une entrée féérique</li>
                    <li><i class="fa-solid fa-circle-check"></i> Projecteur<span> LASER 3D </span>avec effet suivi
                        précis garantie</li>
                    <li><i class="fa-solid fa-circle-check"></i> Grand<span> Écran Mur LED </span>pour des projections
                        spéctaculaires</li>
                    <li><i class="fa-solid fa-circle-check"></i><span> PACK SONO ESSENTIEL </span>pour votre cérémonie
                        nuptiale offert</li>
                    <li><i class="fa-solid fa-circle-check"></i><span> 4 fontaines de jet d'artifice inclus </span>pour
                        sublimer votre ouverture de bal</li>


                </ul>

                <div class="btn flex column align-center">
                    <h5>Obtenez votre devis personalisé dès maintenant !</h5>
                    <button> <a href="index.php?action=devis&type=mariage">Devis</a></button>
                    <h6>Options supplémentaires disponibles : Effets spéciaux (FX), éclairages avancés, feux
                        d’artifice...</h6>
                </div>
            </div>
        </div>
    </section>
    <?php require_once 'footer.php'; // Inclure ton footer ?>

</body>
<script>

document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(link => {
    link.addEventListener('click', function (event) {
        event.preventDefault();
        const targetId = link.getAttribute('href');
        const targetElement = document.querySelector(targetId);

        if (targetElement) {
            targetElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            
        }
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

    function handleResponsivePrivilege() {
        const description = document.getElementById('left');
        const imgPrivilege = document.getElementById('img-privilege');
        const h3 = description.querySelector('p'); // Sélectionne le <h3>
        if (window.innerWidth <= 768) {
            // Déplacer img-essentiel dans description après le <h3>
            if (!description.contains(imgPrivilege)) {
                description.insertBefore(imgPrivilege, h3.nextSibling);
            }
        } else {
            // Remettre img-essentiel à sa position initiale
            const privilege = document.getElementById('privilege_desc');
            if (privilege && !privilege.contains(imgPrivilege)) {
                privilege.appendChild(imgPrivilege);
            }
        }
    }


    // Ajouter un écouteur pour détecter le redimensionnement de la fenêtre
    window.addEventListener('resize', handleResponsiveEssentiel);
    window.addEventListener('resize', handleResponsivePrivilege);

    // Appeler les fonctions au chargement de la page
    document.addEventListener('DOMContentLoaded', handleResponsiveEssentiel);
    document.addEventListener('DOMContentLoaded', handleResponsivePrivilege);
</script>



</html>