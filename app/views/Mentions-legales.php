<?php
    $title = "Mentions Légales";
?>
<main>
    <h1>Mentions Légales</h1>
    <p>Dernière mise à jour : <?= date('d/m/Y') ?></p>

    <h2>1. Informations Légales</h2>
    <p>Nom de l'entreprise : [Nom]</p>
    <p>Statut juridique : [SARL/EI/etc.]</p>
    <p>Siège social : [Adresse]</p>
    <p>Numéro SIRET : [123 456 789 00012]</p>
    <p>Email : <a href="mailto:contact@monsite.com">contact@monsite.com</a></p>

    <h2>2. Hébergeur</h2>
    <p>Nom de l'hébergeur : [OVH / Hostinger / etc.]</p>
    <p>Adresse : [Adresse de l'hébergeur]</p>

    <h2>3. Propriété intellectuelle</h2>
    <p>Tout le contenu du site (textes, images, vidéos) est protégé par les droits d'auteur.</p>

    <h2>4. Responsabilité</h2>
    <p>Nous ne pouvons être tenus responsables en cas de mauvaise utilisation des informations fournies sur le site.</p>

    <h2>5. Données personnelles</h2>
    <p>Pour savoir comment nous traitons les données personnelles, consultez notre <a href="confidentialite.php">Politique de confidentialité</a>.</p>
</main>

<?php require_once 'footer.php'; // Inclure ton footer ?>
