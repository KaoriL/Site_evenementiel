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
    <div class="img">
        <img src="public/assets/image/img-rdv.jpg" alt="">
    </div>
    <?php require_once 'header.php'; // Inclure ton header ?>
    <section>
        <div class="filtre">
            <button class="retour"><a href="index.php?action=home"><</a></button>
                    <h1>Mes rendez-vous</h1>
        </div>

        <?php if (isset($rendez_vous) && !empty($rendez_vous)): ?>
            <ul>
                <?php foreach ($rendez_vous as $rdv): ?>
                    <li class="card">
                        <h2><?= date('d.m.Y', strtotime($rdv['rdv_date'])); ?></h2>
                        <h3> <?= date('H\h i', strtotime($rdv['rdv_horaire'])); ?></h3>
                        <!-- Affichez d'autres informations selon le besoin -->
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun rendez-vous trouvé.</p>
        <?php endif; ?>

    </section>















    <?php require_once 'footer.php'; // Inclure ton footer ?>
</body>

</html>