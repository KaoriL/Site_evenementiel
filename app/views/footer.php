
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/assets/css/footer.css?v=<?php echo time(); ?>">
</head>

<body>
    <footer>
        <div class="img-footer">
            <img src="public/assets/image/img-footer.jpeg" alt="Image d'un couple" class="brightness">
        </div>

        <div class="tableau">
            <div class="foot">
                <h1>DEEJAY 13</h1>
                <p>L'ART DE CÉLÉBRER EN HARMONIE</p>
            </div>
            <div class="foot">
                <h2><a href="index.php?action=home">ACCUEIL</a></h2>
                <a href="">PRESTATIONS</a>
                <a href="">MARIAGE</a>
                <a href="">AVIS</a>
                <a href="">À PROPOS</a>
                <a href="">CONTACT</a>
            </div>
            <div class="foot">
                <h2><a href="index.php?action=home">EN SAVOIR PLUS</a></h2>
                <a
                    href="https://www.google.com/search?q=5+rue+Pelloutier+77183+Croissy+Beaubourg&rlz=1C5CHFA_enFR1126FR1136&oq=5+rue+Pelloutier+77183+Croissy+Beaubourg&gs_lcrp=EgZjaHJvbWUyCQgAEEUYORiABDIKCAEQABiiBBiJBTIKCAIQABiABBiiBDIKCAMQABiABBiiBNIBBzUwMWowajeoAgCwAgA&sourceid=chrome&ie=UTF-8">
                    5 rue Pelloutier 77183 Croissy Beaubourg</a>
                <a href="">06 98 29 26 78</a>
                <a href="">client.djtresor@gmail.com</a>

            </div>
            <div class="mention">
                <ul>
                    <li><a href="https://www.instagram.com/deejay13officiel/?hl=fr"><i
                                class="fa-brands fa-square-instagram" style="color: #ffffff;"></i></a></li>
                    <li><a href="index.php?action=mention-legales">Mentions légales et autres informations en
                            cours..</a></li>
                </ul>


            </div>

        </div>
    </footer>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
    var footer = document.querySelector('.img-footer');
    var imageUrl = footer.getAttribute('public/assets/image/img-footer.jpeg'); // Récupère l'URL de l'image

    if (imageUrl) {
        footer.style.backgroundImage = 'url("' + imageUrl + '")';
        footer.style.backgroundSize = "cover";
        footer.style.backgroundPosition = "center";
    }
});

    </script>
</body>

</html>