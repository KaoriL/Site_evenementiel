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

    <section class="banniere">
        <img src="public/assets/image/img-presta.jpeg" alt="Image en noir et blanc" class="bw-image">
        <div>
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
            <div class="description">
                <h2>MARIAGE</h2>
                <h3>CÉLÉBREZ L’AMOUR AVEC ÉLÉGANCE ET PASSION</h3>
                <p>Plusieurs prestations pour sublimer votre mariage : musique, lumière, et maître de cérémonie, chaque
                    service pensé pour répondre aux besoins de chacun.
                </p>
                <div class="btn">
                    <button> <a href="index.php?action=mariage">En savoir plus</a></button>

                </div>
            </div>
            <div class="img-essentiel">
                <img class="img-border" src="public/assets/image/img-presta-mariage.jpeg" alt="">
            </div>
        </div>

        <div id="gala" class="gala">
            <div class="img-essentiel">
                <img class="img-left" src="public/assets/image/img-presta-left.jpeg" alt="">
                <img class="img-right" src="public/assets/image/img-presta-right.jpeg" alt="">
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
                <img class="img-soiree" src="public/assets/image/img-presta-soiree.jpeg" alt="">
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
    </section>
</body>

</html>