<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="public/assets/css/header.css?v=<?php echo time(); ?>">
</head>

<body>
    <header>
        <!-- From Uiverse.io by JulanDeAlb -->
        <label class="hamburger">
            <input type="checkbox" id="menuToggle">
            <svg viewBox="0 0 32 32">
                <path class="line line-top-bottom"
                    d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22">
                </path>
                <path class="line" d="M7 16 27 16"></path>
            </svg>
        </label>

        <nav><a href=""> À propos</a>
            <a href=""> Avis</a>
            <a href="">Contact</a>
            <a href="">Prestations</a>
            <a href="index.php?action=home">Accueil</a>
        </nav>
        <nav id="navMenu">
            <a href=""><i class="fa-solid fa-users"></i> À propos</a>
            <a href=""><i class="fa-solid fa-comments"></i> Avis</a>
            <a href=""><i class="fa-solid fa-address-book"></i> Contact</a>
            <a href=""><i class="fa-solid fa-calendar"></i> Prestations</a>
            <a href="index.php?action=home"><i class="fa-solid fa-house"></i> Accueil</a>
        </nav>
        <div class="milieu">
            <h2>DEEJAY 13</h2>
            <p>100% JESUS</p>
        </div>

        <button><a class="gradient" href="https://www.instagram.com/deejay13officiel/?hl=fr"><i
                    class="fa-brands fa-instagram"></i> <span>Suivez-nous</span> </a></button>
    </header>

    <script>
        document.getElementById("menuToggle").addEventListener("click", function () {
            let navMenu = document.getElementById("navMenu");
            if (this.checked) {
                navMenu.style.display = "flex";
            } else {
                navMenu.style.display = "none";
            }
        });
        window.addEventListener("scroll", function() {
    const navbar = document.querySelector("header");
    if (window.scrollY > 50) { // Change après 50px de scroll
        navbar.classList.add("scrolled");
    } else {
        navbar.classList.remove("scrolled");
    }
});

    </script>
</body>

</html>