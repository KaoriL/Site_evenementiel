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


    // Vérifie si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        echo "Erreur : utilisateur non connecté.";
        header('Location: login.php');  // Redirige vers la page de login
        exit;
    }

    // Si connecté, afficher les infos de l'utilisateur
//echo "Bonjour " . $_SESSION['username'];
    ?>
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
            <div class="description">
                <h2>PACK CONFORT</h2>
                <h3>PARCE QUE VOUS LE MERITEZ</h3>
                <p>Un pack pensé pour votre bien-être
                    et votre tranquillité, avec tout ce dont
                    vous avez besoin pour briller sans stress.
                </p>

                <button> <a href="index.php?action=devis&type=mariage">Je veux ce moment de confort</a></button>
            </div>
            <div class="img-essentiel">

                <img class="img-border" src="public/assets/image/img-confort-accueil.jpg" alt="">
                <img class="img-door" src="public/assets/image/img-confort-accueil2.jpg" alt="">
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
            <div class="moment_photo">
                <img src="public/assets/image/img-gala-accueil.png" alt="">
                <img src="public/assets/image/img-gala-accueil.png" alt="">
                <img src="public/assets/image/img-gala-accueil.png" alt="">
                <div class="btn-r">
                    <p>FAITES PARTIE DE 
                    NOS SUCCESS STORIES</p>
                    <div class="line"></div>
                    <button> <a href="index.php?action=devis">Confiez nous votre évènement</a></button>
                </div>
            </div>
        </div>
    </section>
    <?php require_once 'footer.php'; // Inclure ton footer ?>
</body>

</html>