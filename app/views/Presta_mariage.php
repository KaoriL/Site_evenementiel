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

    <section class="centre">
        <div class="ruban">
            <p>GALA • CONFÉRENCES • ANNIVERSAIRE • MARIAGE • SÉMINAIRE • SOIRÉE PRIVÉE • AFTER WORK </p>
        </div>

        <div class="pack">
            <h2>NOS PACKS</h2>
            <p>UNE OFFRE ADAPTÉE À TOUS VOS ÉVÉNEMENTS</p>
            <div class="sommaire">
                <ul>
                    <li><a href="">Pack Essentiel</a> - L’indispensable pour une ambiance réussie </li>
                    <li><a href="">Pack Confort</a> - Un niveau supérieur pour plus d'animations et d'effets</li>
                    <li><a href="">Pack Privilège</a> - Une mise en scène immersive et spéctaculaire</li>
                    <li><a href="">Pack Royal Dream</a> - L'expérience ultime pour un événement inoubliable</li>
                </ul>
            </div>
        </div>

        <div class="essentiel">
            <div class="description">
            <h2>PACK ESSENTIEL</h2>
            <h3>L'INCONTOURNABLE POUR UNE AMBIANCE RÉUSSIE</h3>
            <p>L'essentiel : une soirée animée avec un DJ professionnel et un maître de cérémonie pour une ambiance garantie</p>
            </div>
            <div><img src="public/assets/image/img-essentiel.jpeg" alt=""></div>


        </div>


        <a href="index.php?action=devis&type=mariage">Devis</a>
    </section>

</body>

</html>