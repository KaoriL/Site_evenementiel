<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devis</title>
    <link rel="stylesheet" href="public/assets/css/devis.css?v=1.1">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        .available {
            background-color: green !important;
            color: white !important;
        }

        .unavailable {
            background-color: gray !important;
            color: white !important;
            pointer-events: none;
            opacity: 0.6;
        }

        .flatpickr-day.available {
            background: #28a745 !important;
            /* Vert */
            color: white !important;
            border-radius: 5px;
        }

        .horaire-btn {
            margin: 5px;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .horaire-btn:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <a href="">À propos</a>
            <a href="">Avis</a>
            <a href="">Contact</a>
            <a href="">Prestations</a>
            <a href="">Accueil</a>
        </nav>

        <div>
            <h2>DEEJAY 13</h2>
            <p>100% JESUS</p>
        </div>

        <button><a class="gradient" href="https://www.instagram.com/deejay13officiel/?hl=fr">Suivez-nous</a></button>

    </header>

    <section class="banniere">

        <img src="public/assets/image/img-devis.jpeg" alt="Image en noir et blanc" class="bw-image">
        <div>
            <h1>
                DEVIS
            </h1>
        </div>

    </section>

    <section class="centre">

        <div class="fond">
            <p>
                Afin de mieux vous accompagner dans la préparation de votre événement, nous vous proposons une prise de
                rendez-vous en visio. Cela nous permettra de discuter de vos besoins spécifiques et de vous fournir un
                devis clair, détaillé et parfaitement adapté à vos attentes.
            </p>
            <div class="fond2"></div>
        </div>

        <div class="formulaire">
            <form action="index.php?action=devis" method="POST">
                <div class="dessus">
                    <h2>DEEJAY 13</h2>
                    <p>100% JESUS</p>
                    <h3>Demande de devis personnalisé</h3>
                    <h4>Prise de rendez-vous en ligne(en visio)</h4>
                </div>
                <div class="rdv">
                    <div class="informations">

                        <label for="nom">Nom<span style="color:#A60000;">*</span></label>
                        <input placeholder="Votre nom" type="text" id="nom" name="nom" required>

                        <label for="prenom">Prenom<span style="color:#A60000;">*</span></label>
                        <input placeholder="Votre prénom" type="text" id="prenom" name="prenom" required>

                        <label for="email">Email<span style="color:#A60000;">*</span></label>
                        <input placeholder="Votre E-mail" type="email" id="email" name="email" required>

                        <label for="telephone">Téléphone<span style="color:#A60000;">*</span> </label>
                        <input placeholder="Votre numéro de téléphone" type="text" id="telephone" name="telephone"
                            required>
                    </div>

                    <div class="informations">
                        <label for="date_evenement">Date de votre événement<span style="color:#A60000;">*</span>
                        </label>
                        <input type="date" id="date_evenement" name="date_evenement" required>


                        <label for="rdv_date">Choisissez votre date de rendez-vous<span
                                style="color:#A60000;">*</span></label>
                        <input type="text" id="datepicker" name="rdv_date" placeholder="Sélectionnez une date">

                        <label for="rdv_horaire">Choisissez votre horaire<span style="color:#A60000;">*</span></label>
                        <input type="hidden" id="disponibilite_id" name="disponibilite_id">

                        <select id="horaire_select" name="rdv_horaire" required>
                            <option value="">-- Sélectionnez un horaire --</option>
                        </select>



                        <label for="service">Prestation<span style="color:#A60000;">*</span></label>
                        <select id="service" name="service" required>
                            <option value="">-- Sélectionnez une prestation --</option>
                            <option value="gala">Gala</option>
                            <option value="soiree_privee">Soirée Privée</option>
                            <option value="anniversaire">Anniversaire</option>
                            <option value="conference">Conférence</option>
                            <option value="after_work">After work</option>
                            <option value="autre">Demande personnalisée</option>
                        </select>

                        <label for="lieu">Lieu de l'événement<span style="color:#A60000;">*</span></label>
                        <input type="text" id="lieu" name="lieu" required>
                    </div>

                    <div class="description"> <label for="message">Description</label>
                        <textarea placeholder="Description de votre événement, ce que vous souhaitez" id="message"
                            name="message" rows="5"></textarea>
                    </div>
                </div>

                <button type="submit" value="envoyer">Envoyer</button>
            </form>
        </div>

    </section>
</body>



<script>

document.addEventListener("DOMContentLoaded", function () {
    fetch('index.php?action=disponibilites')
        .then(response => response.json())
        .then(data => {
            // Créer une map pour stocker les créneaux et leurs IDs
            let disponibilitesMap = {};
            let disponibilitesParDate = {};

            data.forEach(item => {
                let key = item.date_disponible + '-' + item.horaire;
                disponibilitesMap[key] = item.id;  // Lier l'ID du créneau avec la combinaison date-horaire

                if (!disponibilitesParDate[item.date_disponible]) {
                    disponibilitesParDate[item.date_disponible] = [];
                }

                if (item.est_reserve == 0) {  // Si le créneau est disponible
                    disponibilitesParDate[item.date_disponible].push(item.horaire);
                }
            });

            // Initialisation de flatpickr
            flatpickr("#datepicker", {
                dateFormat: "Y-m-d",
                disable: [
                    function (date) {
                        let dateStr = date.toISOString().split('T')[0];
                        return !disponibilitesParDate[dateStr];  // Désactiver les dates sans créneaux
                    }
                ],
                onChange: function (selectedDates, dateStr) {
                    let horaireSelect = document.getElementById("horaire_select");
                    horaireSelect.innerHTML = '<option value="">-- Sélectionnez un horaire --</option>';

                    // Ajouter les horaires disponibles pour cette date
                    if (disponibilitesParDate[dateStr]) {
                        disponibilitesParDate[dateStr].forEach(horaire => {
                            let option = document.createElement("option");
                            option.value = horaire;
                            option.textContent = horaire;
                            horaireSelect.appendChild(option);
                        });
                    }
                }
            });

            // Quand un horaire est sélectionné, on récupère l'ID du créneau
            document.getElementById("horaire_select").addEventListener("change", function () {
                let selectedHoraire = this.value;
                let selectedDate = document.getElementById("datepicker").value;

                // Créer une clé unique combinant date et horaire pour retrouver l'ID
                let selectedKey = selectedDate + '-' + selectedHoraire;
                let disponibiliteId = disponibilitesMap[selectedKey];

                // Assigner l'ID au champ caché
                document.getElementById("disponibilite_id").value = disponibiliteId;
            });
        })
        .catch(error => console.error('Erreur de récupération des données:', error));
});



    const emailInput = document.getElementById('email');
    emailInput.addEventListener('blur', () => {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(emailInput.value)) {
            alert('Veuillez entrer un email valide.');
        }
    });

    const eventDateInput = document.getElementById('date_evenement');
    const today = new Date().toISOString().split('T')[0];  // Récupère la date d'aujourd'hui

    eventDateInput.setAttribute('min', today);  // Définit la date minimale à aujourd'hui



</script>



</html>