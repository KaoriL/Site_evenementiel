<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes rendez-vous</title>
    <link rel="stylesheet" href="public/assets/css/index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="public/assets/css/rdv.css?v=<?php echo time(); ?>">
</head>

<body>
<?php require_once 'header.php'; // Inclure ton header ?>
<section class="banniere">
    <img src="public/assets/image/img-accueil.jpeg" alt="Image en noir et blanc" class="bw-image">
    <div>
        <h1>Mes Rendez-vous
        </h1>
    </div>
</section>
    
    <section class="center">
   <?php if (isset($rendez_vous) && !empty($rendez_vous)): ?>
    <ul class="column">
        <h5>Mes rendez-vous à venir</h5>
        <?php foreach ($rendez_vous as $rdv): ?>
            <li class="card">
                <!-- Affichez d'autres informations selon le besoin -->
                <div class="card-border-top">
                </div>
                <div class="img">
                </div>
                <span>
                    <h2><?= date('d.m.Y', strtotime($rdv['rdv_date'])); ?></h2>
                </span>
                <p class="job">
                <h3> <?= date('H\h i', strtotime($rdv['rdv_horaire'])); ?></h3>
                </p>
                <button> Lien zoom
                </button>
            </li>

            <div>

            </div>

        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun rendez-vous trouvé.</p>
<?php endif; ?>
        
    </section>

    <?php require_once 'footer.php'; // Inclure ton footer ?>
</body>

</html>