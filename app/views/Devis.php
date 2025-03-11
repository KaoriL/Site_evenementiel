<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devis</title>
    <link rel="stylesheet" href="public/assets/css/devis.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="public/assets/css/index.css?v=<?php echo time(); ?>">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/fr.js"></script>


    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <?php require_once 'header.php'; // Inclure ton header ?>
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
                Afin de mieux vous accompagner dans la préparation de votre événement,
                nous vous proposons un rendez-vous en visioconférence. Cela nous permettra de discuter de vos besoins
                spécifiques et de vous fournir un devis clair,
                détaillé et parfaitement adapté à vos attentes.


            </p>
            <div class="fond2"></div>
        </div>


        <div class="formulaire">

            <?php $typeFormulaire = isset($_GET['type']) && $_GET['type'] == 'mariage' ? 'mariage' : 'standard';
            if ($typeFormulaire == 'mariage'): ?>
                <form id="form-devis" action="index.php?action=devis_mariage" method="POST">
                    <div class="dessus">
                        <h2>DEEJAY 13</h2>
                        <p>100% JESUS</p>
                        <h3>Demande de devis personnalisé</h3>
                        <h4>Prise de rendez-vous en ligne</h4>
                        <h5>(En visioconférence)</h5>
                    </div>
                    <div class="rdv"></div>
                    <div class="info">
                        <div class="informations">
                            <label for="nom_marie">Nom du marié<span style="color:#A60000;">*</span></label>
                            <input placeholder="Nom du marié" type="text" id="nom_marie" name="nom_marie" required>

                            <label for="prenom_marie">Prenom du marié<span style="color:#A60000;">*</span></label>
                            <input placeholder="Prenom du marié" type="text" id="prenom_marie" name="prenom_marie" required>

                            <label for="email_marie">Email du marié<span style="color:#A60000;">*</span></label>
                            <input placeholder="Email du marié" type="email" id="email_marie" name="email_marie" required>
                            <div id="error-email_marie" class="error-message"></div>

                            <label for="telephone_marie">Téléphone du marié<span style="color:#A60000;">*</span> </label>
                            <input placeholder="Téléphone du marié" type="text" id="telephone_marie" name="telephone_marie"
                                maxlength="20" required>
                            <div id="error-telephone_marie" class="error-message"></div>

                            <label for="age_marie">Age du marié</label>
                            <input placeholder="Age du marié" type="text" id="age_marie" name="age_marie">

                            <label for="origine_marie">Origine du marié<span style="color:#A60000;">*</span> </label>
                            <input placeholder="Origine du marié" type="text" id="origine_marie" name="origine_marie"
                                required>


                        </div>
                        <div class="informations">

                            <label for="nom_mariee">Nom de la mariée<span style="color:#A60000;">*</span></label>
                            <input placeholder="Nom de la mariée" type="text" id="nom_mariee" name="nom_mariee" required>

                            <label for="prenom_mariee">Prenom de la mariée<span style="color:#A60000;">*</span></label>
                            <input placeholder="Prenom de la mariée" type="text" id="prenom_mariee" name="prenom_mariee"
                                required>

                            <label for="email_mariee">Email de la mariée<span style="color:#A60000;">*</span></label>
                            <input placeholder="Email de la mariée" type="email" id="email_mariee" name="email_mariee"
                                required>
                            <div id="error-email_mariee" class="error-message"></div>

                            <label for="telephone_mariee">Téléphone de la mariée<span style="color:#A60000;">*</span>
                            </label>

                            <div id="error-telephone_mariee" class="error-message"></div>
                            <input placeholder="Téléphone de la mariée" type="text" id="telephone_mariee"
                                name="telephone_mariee" maxlength="20" required>

                            <label for="age_mariee">Age de la mariée </label>
                            <input placeholder="Age de la  mariée" type="text" id="age_mariee" name="age_mariee">
                            <label for="origine_mariee">Origine de la mariée<span style="color:#A60000;">*</span> </label>
                            <input placeholder="Origine de la mariée" type="text" id="origine_mariee" name="origine_mariee"
                                required>
                        </div>
                    </div>
                    <div class="description">
                        <div class="evenement">
                            <label for="date_evenement">Date de votre événement
                            </label>
                            <input type="date" id="date_evenement" name="date_evenement">
                            <label for="rdv_date">Choisissez votre date de rendez-vous<span
                                    style="color:#A60000;">*</span></label>
                            <input type="text" id="datepicker" name="rdv_date" placeholder="Sélectionnez une date">
                            <label for="rdv_horaire">Choisissez votre horaire<span style="color:#A60000;">*</span></label>
                            <input type="hidden" id="disponibilite_id" name="disponibilite_id">
                            <select id="horaire_select" name="rdv_horaire" required>
                                <option value="">-- Sélectionnez un horaire --</option>
                            </select>
                        </div>

                        <div class="evenement">
                            <label for="service">Prestation<span style="color:#A60000;">*</span></label>
                            <select id="service" name="service" required>
                                <option value="">-- Sélectionnez une prestation --</option>
                                <option value="Pack Essentiel">Pack Essentiel</option>
                                <option value="Pack Confort">Pack Confort</option>
                                <option value="Pack Privilège">Pack Privilège</option>
                                <option value="Pack Royal Dream">Pack Royal Dream</option>
                                <option value="Demande personnalisée">Demande personnalisée</option>
                            </select>
                            <label for="lieu">Lieu de l'événement<< /label>
                                    <input type="text" id="lieu" name="lieu" placeholder="Paris">

                        </div>
                    </div>
                    <label style="margin-top:30px;margin-bottom:20px; " for="message">Racontez-nous tous les détails de
                        votre événement</label>
                    <textarea
                        placeholder="Décrivez votre événement : type, date, lieu, nombre d'invités, ambiance souhaitée..."
                        id="message" name="message" rows="5"></textarea>

                    <button id="open-popup" type="submit" value="envoyer">Envoyer</button>

                    <div class="overlay" id="overlay"></div>
                    <div class="popup" id="popup">
                        <div class="loader"></div>
                    </div>
                </form>


            <?php else: ?>
                <form id="form-devis" action="index.php?action=devis" method="POST">
                    <div class="dessus">
                        <h2>DEEJAY 13</h2>
                        <p>100% JESUS</p>
                        <h3>Demande de devis personnalisé</h3>
                        <h4>Prise de rendez-vous en ligne</h4>
                        <h5>(En visioconférence)</h5>
                    </div>
                    <div class="rdv">
                        <div class="informations">

                            <label for="nom">Nom<span style="color:#A60000;">*</span></label>
                            <input placeholder="Votre nom" type="text" id="nom" name="nom" required>

                            <label for="prenom">Prenom<span style="color:#A60000;">*</span></label>
                            <input placeholder="Votre prénom" type="text" id="prenom" name="prenom" required>

                            <label for="email">Email<span style="color:#A60000;">*</span></label>
                            <input placeholder="Votre E-mail" type="email" id="email" name="email" required>
                            <div id="error-email" class="error-message"></div>

                            <label for="telephone">Téléphone<span style="color:#A60000;">*</span> </label>
                            <input placeholder="Votre numéro de téléphone" type="text" id="telephone" name="telephone"
                                maxlength="20" required>
                            <div id="error-telephone" class="error-message"></div>
                        </div>

                        <div class="informations">
                            <label for="date_evenement">Date de votre événement
                            </label>
                            <input type="date" id="date_evenement" name="date_evenement">


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
                                <option value="Gala">Gala</option>
                                <option value="Soiree_privee">Soirée Privée</option>
                                <option value="Anniversaire">Anniversaire</option>
                                <option value="Conference">Conférence</option>
                                <option value="After_work">After work</option>
                                <option value="Autre">Demande personnalisée</option>
                            </select>

                            <label for="lieu">Lieu de l'événement</label>
                            <input type="text" id="lieu" name="lieu" placeholder="Paris">
                        </div>
                    </div>
                    <div class="description"> <label style="margin-top:30px;margin-bottom:20px; "
                            for="message">Racontez-nous tous les détails de votre événement</label>
                        <textarea
                            placeholder="Décrivez votre événement : type, date, lieu, nombre d'invités, ambiance souhaitée..."
                            id="message" name="message" rows="5"></textarea>
                    </div>
                    <button id="open-popup" type="submit" value="envoyer">Envoyer</button>
                    <div class="overlay" id="overlay"></div>
                    <div class="popup" id="popup">
                        <div class="loader"></div>
                    </div>
            </div>
            </form>
        <?php endif; ?>
        </div>
    </section>
    <?php require_once 'footer.php'; // Inclure ton footer ?>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Récupération des disponibilités
        fetch('index.php?action=disponibilites')
            .then(response => response.json())
            .then(data => {
                // Vérification de la réponse
                // console.log("📩 Réponse brute du serveur:", data);

                // Traitement des disponibilités
                let disponibilitesMap = {};
                let disponibilitesParDate = {};

                // Stockage des disponibilités par date
                data.forEach(item => {
                    const key = `${item.date_disponible}-${item.horaire}`;
                    disponibilitesMap[key] = item.id;
                    if (!disponibilitesParDate[item.date_disponible]) {
                        disponibilitesParDate[item.date_disponible] = [];
                    }
                    if (item.est_reserve === 0) { // Créneaux disponibles
                        disponibilitesParDate[item.date_disponible].push(item.horaire);
                    }
                });

                const datepicker = document.getElementById("datepicker");
                if (datepicker) {
                    let today = new Date();
                    let tomorrow = new Date();
                    tomorrow.setDate(today.getDate() + 1);
                    let tomorrowStr = tomorrow.toISOString().split('T')[0];


                    // Configurer flatpickr
                    flatpickr(datepicker, {
                        dateFormat: "Y-m-d",
                        minDate: tomorrowStr, // Correction du problème "tomorrow"
                        locale: flatpickr.l10ns.fr, // Active la langue française
                        disable: [
                            function (date) {
                                const dateStr = `${date.getFullYear()}-${('0' + (date.getMonth() + 1)).slice(-2)}-${('0' + date.getDate()).slice(-2)}`;
                                console.log("Vérification de la date:", dateStr);

                                // Bloquer les dates qui ne sont pas dans les disponibilités
                                if (!disponibilitesParDate[dateStr]) {
                                    console.log("🔴 Bloqué (pas de disponibilité) :", dateStr);
                                    return true;
                                }

                                // Si la date est dans les disponibilités, alors on permet de la sélectionner
                                return false;
                            }

                        ],
                        onChange: (selectedDates, dateStr) => {
                            const horaireSelect = document.getElementById("horaire_select");
                            horaireSelect.innerHTML = '<option value="">-- Sélectionnez un horaire --</option>';
                            if (disponibilitesParDate[dateStr]) {
                                disponibilitesParDate[dateStr].forEach(horaire => {
                                    const option = document.createElement("option");
                                    option.value = horaire;
                                    option.textContent = horaire;
                                    horaireSelect.appendChild(option);
                                });
                            }
                        }
                    });
                } else {
                    console.error("⛔ L'élément #datepicker est introuvable.");
                }


                // Gestion du changement d'horaire
                const horaireSelect = document.getElementById("horaire_select");
                if (horaireSelect) {
                    horaireSelect.addEventListener("change", function () {
                        const selectedHoraire = this.value;
                        const selectedDate = datepicker.value;
                        const selectedKey = `${selectedDate}-${selectedHoraire}`;
                        const disponibiliteId = disponibilitesMap[selectedKey];
                        const disponibiliteIdInput = document.getElementById("disponibilite_id");
                        if (disponibiliteIdInput) {
                            disponibiliteIdInput.value = disponibiliteId;
                        }
                    });
                } else {
                    console.error("⛔ L'élément #horaire_select est introuvable.");
                }

                // Validation des e-mails
                const emailFields = ['email', 'email_marie', 'email_mariee'];
                emailFields.forEach(fieldId => {
                    const emailInput = document.getElementById(fieldId);
                    if (emailInput) {
                        emailInput.addEventListener('blur', () => {
                            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            if (!emailPattern.test(emailInput.value)) {
                                document.getElementById('erreur').style.display = 'block';
                            } else {
                                document.getElementById('erreur').style.display = 'none';
                            }
                        });
                    }
                });

                // Désactiver la date du jour pour la date de l'événement
                const eventDateInput = document.getElementById('date_evenement');
                if (eventDateInput) {
                    let tomorrow = new Date();
                    tomorrow.setDate(tomorrow.getDate() + 1); // On passe à demain
                    eventDateInput.setAttribute('min', tomorrow.toISOString().split('T')[0]);
                } else {
                    console.error("⛔ L'élément #date_evenement est introuvable.");
                }

            })
            .catch(error => console.error('🚨 Erreur de récupération des données:', error));
    });

    document.getElementById("form-devis").addEventListener("submit", function (event) {
        event.preventDefault(); // Bloque l'envoi par défaut

        let champsObligatoires;

        if ('<?php echo $typeFormulaire; ?>' === 'mariage') {
            champsObligatoires = ["nom_marie", "prenom_marie", "nom_mariee", "prenom_mariee",
                "email_marie", "email_mariee", "telephone_marie", "telephone_mariee",
                "service"];
        } else {
            champsObligatoires = ["nom", "prenom", "email", "telephone", 'service'];
        }

        let erreurs = [];

        champsObligatoires.forEach(function (champ) {
            let input = document.getElementById(champ);
            if (!input || input.value.trim() === "") {
                erreurs.push("Le champ " + champ + " est obligatoire.");
            }
        });

        if (erreurs.length > 0) {
            alert("Erreur : " + erreurs.join("\n"));
        } else {
            console.log("✅ Formulaire valide, soumission en cours...");
            // Si aucune erreur, afficher le pop-up

            document.getElementById('overlay').style.display = 'block';
            document.getElementById('popup').style.display = 'block';

            document.getElementById("form-devis").submit(); // Envoie réel du formulaire
        }
    });

    // Validation du téléphone (uniquement chiffres + max 20 caractères)
    function validatePhoneNumber(input) {
        input.value = input.value.replace(/\D/g, ""); // Supprime tout sauf les chiffres
        if (input.value.length > 20) {
            input.value = input.value.substring(0, 20);
        }
    }
    // Vérification de l'email
    function validateEmail(input) {
        let emailRegex = /^[a-zA-Z0-9._%+-]+@(gmail|outlook|icloud|yahoo)\.(com|fr|net|org|[a-z]{2,})$/i;
        let errorDiv = document.getElementById("error-" + input.id);
        if (input.value.trim() !== "" && !emailRegex.test(input.value)) {
            errorDiv.textContent = "Veuillez entrer une adresse email valide.";
        } else {
            errorDiv.textContent = "";
        }
    }
    // Ajout des écouteurs pour les champs mariage
    if ('<?php echo $typeFormulaire; ?>' == "mariage") {
        ["telephone_marie", "telephone_mariee"].forEach(function (id) {
            let input = document.getElementById(id);
            if (input) {
                input.addEventListener("input", function () {
                    validatePhoneNumber(this);
                });
            }
        });
        ["email_marie", "email_mariee"].forEach(function (id) {
            let input = document.getElementById(id);
            if (input) {
                input.addEventListener("input", function () {
                    validateEmail(this);
                });
            }
        });
    } else {
        // Appliquer les validations aux inputs téléphone et email
        document.getElementById("telephone").addEventListener("input", function () {
            validatePhoneNumber(this);
        });
        document.getElementById("email").addEventListener("input", function () {
            validateEmail(this);
        });
    };

    <?php if (isset($_SESSION['message'])): ?>
  
        
        window.onload = function() {
            // Création du pop-up
            var modal = document.createElement('div');
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100%';
            modal.style.height = '100%';
            modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
            modal.style.display = 'flex';
            modal.style.alignItems = 'center';
            modal.style.justifyContent = 'center';
            modal.style.zIndex = '9999';

            // Contenu du pop-up
            var modalContent = document.createElement('div');
            modalContent.style.backgroundColor = '#fff';
            modalContent.style.padding = '20px';
            modalContent.style.borderRadius = '8px';
            modalContent.style.textAlign = 'center';

            var message = document.createElement('p');
            message.textContent = '<?php echo $_SESSION['message']; ?>';
            modalContent.appendChild(message);

            var loginButton = document.createElement('button');
            loginButton.textContent = 'Connectez-vous';
            loginButton.onclick = function() {
                window.location.href = 'login.php'; // Redirige vers la page de connexion
            };
            modalContent.appendChild(loginButton);

            var signupButton = document.createElement('button');
            signupButton.textContent = 'Inscrivez-vous';
            signupButton.onclick = function() {
                window.location.href = 'signup.php'; // Redirige vers la page d'inscription
            };
            modalContent.appendChild(signupButton);

            modal.appendChild(modalContent);
            document.body.appendChild(modal);
        }
    
<?php 
    // On enlève le message de la session une fois qu'il est affiché
    unset($_SESSION['message']); 
?>
<?php endif; ?>



</script>



</html>