<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/Projet_deejay13/public/assets/css/confirmation.css?v=1.8">

</head>

<body>

    <div class="container">
        <?php if (isset($_SESSION['flash_source']) && $_SESSION['flash_source'] === "devis"): ?>
            <h2>Rendez-vous pris avec succès !</h2>
            <h4><i class="fa-solid fa-check"></i></h4>
            <p>Nous vous avons envoyé un e-mail de confirmation. <br>
                Vous allez être redirigé vers la page d'accueil. <br>
                Si la redirection ne fonctionne pas, <a href="index.php?action=home">cliquez-ici</a></p>
            <h3>Deejay13 vous dit à très vite</h3>

        <?php elseif (isset($_SESSION['flash_source']) && $_SESSION['flash_source'] === "commentaire"): ?>
            <div class="alert alert-success">
                <?= $_SESSION['flash_message']; ?>
                <p> 
                    Vous allez être redirigé vers la page d'accueil. <br>
                    Si la redirection ne fonctionne pas, <a href="index.php?action=home">cliquez-ici</a></p>
                <h3>Deejay13 vous dit à très vite</h3>
            </div>
        <?php endif; ?>

        <!-- Suppression des messages après affichage -->
        <?php unset($_SESSION['flash_message']); ?>
        <?php unset($_SESSION['flash_source']); ?>
    </div>

    <script>
        setTimeout(function () {
            window.location.href = 'index.php?action=home';
        }, 10000);  
    </script>
</body>

</html>