import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import frLocale from "@fullcalendar/core/locales/fr";
import Swal from "sweetalert2";

document.getElementById("dateDisplay").innerText = new Date()
  .toLocaleDateString("fr-FR")
  .replace(/\//g, "-");

var CalendarApp = (function () {
  // Fonction pour gérer l'affichage des événements et le bouton "Voir plus"
  function handleEventDisplay(eventsContainer) {
    // Obtenir tous les événements
    const events = eventsContainer.querySelectorAll(".fc-event");

    // Limiter à 2 événements visibles
    const maxVisible = 2;

    // Si plus de 2 événements, on cache les événements excédentaires
    if (events.length > maxVisible) {
      for (let i = maxVisible; i < events.length; i++) {
        events[i].style.display = "none"; // Cacher les événements supplémentaires
      }

      // Créer le bouton "Voir plus"
      const seeMoreButton = document.createElement("button");
      seeMoreButton.textContent = "Voir plus";
      seeMoreButton.classList.add("see-more-button"); // Ajouter une classe CSS pour le styliser
      eventsContainer.appendChild(seeMoreButton);

      // Ajouter un événement de clic pour afficher les événements supplémentaires
      seeMoreButton.addEventListener("click", function () {
        for (let i = maxVisible; i < events.length; i++) {
          events[i].style.display = "block"; // Afficher les événements cachés
        }
        seeMoreButton.style.display = "none"; // Cacher le bouton "Voir plus"
      });
    }
  }

  var initCalendar = function () {
    var calendarEl = document.getElementById("kt_docs_fullcalendar_selectable");
    if (!calendarEl) {
      console.error("Élément #kt_docs_fullcalendar_selectable introuvable !");
      return;
    }

    fetch("index.php?action=getPrestationsParDate")
      .then((response) => response.json())
      .then((data) => {
        console.log("Données récupérées :", data); // Vérification
        const events = data
          .filter((item) => item.est_reserve === 1) // Ne montrer que les réservations
          .map((item) => ({
            title: `Réservé (${item.horaire.slice(0, 5)})`, // Garde que "HH:MM"
            start: `${item.date_disponible}T${item.horaire}`,
            end: `${item.date_disponible}`,
            color: item.color,
            id: item.disponibilite_id, // Utiliser l'ID de la disponibilité ici
            extendedProps: {
              prestation_type: item.prestation_type,
              prestation_id: item.id, // Ajout de l'ID de la prestation
              disponibilite_id: item.disponibilite_id,
              client_email: item.client_email,
              username: item.username, // Nom d'utilisateur

              nom_marie: item.nom_marie || "Inconnu",
              prenom_marie: item.prenom_marie || "Inconnu",
              nom_mariee: item.nom_mariee || "Inconnue",
              prenom_mariee: item.prenom_mariee || "Inconnue",
              telephone_marie: item.telephone_marie || "Non renseigné",
              telephone_mariee: item.telephone_mariee || "Non renseigné",
              origine_marie: item.origine_marie || "Non précisée",
              origine_mariee: item.origine_mariee || "Non précisée",

              // Infos générales
               nom: item.nom || null,  
               prenom: item.prenom || null, 
              telephone: item.telephone || "Non renseigné",
              service: item.service || "Non spécifié",
              lieu: item.lieu || "Non spécifié",
              date_evenement: item.date_evenement || "Non précisé",
             
             
            },
          }));

        var calendar = new Calendar(calendarEl, {
          plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
          initialDate: new Date().toISOString().split("T")[0],
          locale: frLocale,
          headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,timeGridDay",
          },
          navLinks: true,
          selectable: true,
          selectMirror: true,
          editable: true,
          dayMaxEvents: true,
          eventDisplay: 'block', // 👈 important pour virer la bille
          events: events,
          eventDidMount: function(info) {
            const color = info.event.backgroundColor || info.event.extendedProps.color;
            if (color) {
              info.el.style.backgroundColor = color;
              info.el.style.border = "none";
            }
          },

          // Event click pour afficher les détails
          eventClick: function (info) {
            // Récupérer les informations de l'événement
            const event = info.event;
            const eventName = event.title;
            const prestationId = event.extendedProps.prestation_id; // Ajout de l'ID de la prestation
            const disponibiliteId = event.id;

            const prestationType = event.extendedProps.prestation_type; // Type de prestation
            const clientEmail = event.extendedProps.client_email; // Email du client pour l'envoi de mail

            const dateEvenement =
              event.extendedProps.date_evenement || "Non spécifiée";
            const lieu = event.extendedProps.lieu || "Non spécifié";
            const message = event.extendedProps.message || "Aucun message";
            const service = event.extendedProps.service || "Non spécifié";

            // Déterminer les noms, prénoms et origines en fonction du type de prestation
            let clientNomPrenom = "Non spécifié";
            let origine = "Non spécifiée";
            let telephone = "Non spécifié";
            if (prestationType === "mariage") {
              const nomMariee = event.extendedProps.nom_mariee || "Inconnue";
              const prenomMariee = event.extendedProps.prenom_mariee || "Inconnue";
              const nomMarie = event.extendedProps.nom_marie || "Inconnu";
              const prenomMarie = event.extendedProps.prenom_marie || "Inconnu";
          
              clientNomPrenom = `${nomMariee} ${prenomMariee} & ${nomMarie} ${prenomMarie}`;
              origine = `${event.extendedProps.origine_mariee || "Non précisée"} & ${event.extendedProps.origine_marie || "Non précisée"}`;
          
              const telephoneMariee = event.extendedProps.telephone_mariee || "Non renseigné";
              const telephoneMarie = event.extendedProps.telephone_marie || "Non renseigné";
              telephone = `${telephoneMariee} & ${telephoneMarie}`;
          } else if (prestationType === "standard") {
              clientNomPrenom = `${event.extendedProps.nom || "Inconnu"} ${event.extendedProps.prenom || "Inconnu"}`;
              origine = "Non précisée"; // Pas d'origine en standard
              telephone = event.extendedProps.telephone || "Non renseigné";
          }
            // Afficher une fenêtre modale avec les détails de l'événement et les boutons
            Swal.fire({
              title: eventName,
              html: `<div style="text-align: left; font-size: 16px;">
             <!-- <p><strong>ID de la prestation :</strong> ${prestationId}</p>
              <p><strong>ID de la disponibilité :</strong> ${disponibiliteId}</p>-->
              <p><strong>Nom du client :</strong> ${clientNomPrenom}</p>
              <p><strong>Origine :</strong> ${origine}</p>
              <p><strong>Téléphone :</strong> ${telephone}</p>
              <p><strong>Email du client :</strong> ${clientEmail}</p>
              <p><strong>Date de l'événement :</strong> ${dateEvenement}</p>
              <p><strong>Lieu :</strong> ${lieu}</p>
              <p><strong>Message :</strong> ${message}</p>
              <p><strong>Type de prestation :</strong> ${prestationType}</p>
              <p><strong>Service :</strong> ${service}</p>
              <div class="mt-3">
                  <button id="moveEvent" class="btn btn-info mr-2">Déplacer</button>
                  <button id="deleteEvent" class="btn btn-danger">Supprimer</button>
              </div>
              </div>`,
              showCancelButton: false,
              focusConfirm: false,
              customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-active-light",
              },
              buttonsStyling: false,
              didOpen: function () {
                // Gestion des actions des boutons après l'ouverture de la fenêtre
                document
                  .getElementById("moveEvent")
                  .addEventListener("click", function () {
                    // Ouvrir un autre Swal pour déplacer l'événement avec un Flatpickr
                    Swal.fire({
                      title: "Choisissez une nouvelle date et heure",
                      html: `<input type="text" id="flatpickr" class="form-control" />
                      <label for="reason">Motif de déplacement :</label>
                       <select id="reason" class="form-control">
                         <option value="personal">Raison personnelle</option>
                         <option value="medical">Raison médicale</option>
                         <option value="other">Autre</option>
                       </select>`,

                      didOpen: function () {
                        flatpickr("#flatpickr", {
                          enableTime: true,
                          dateFormat: "Y-m-d H:i",
                          defaultDate: event.start, // Pré-sélectionner la date de l'événement actuel
                        });
                      },
                      showCancelButton: true,
                      confirmButtonText: "Déplacer",
                      cancelButtonText: "Annuler",
                      customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-active-light",
                      },
                    }).then((result) => {
                      if (result.isConfirmed) {
                        const newDate =
                          document.getElementById("flatpickr").value;
                        const reason = document.getElementById("reason").value; // Récupérer la raison sélectionnée

                        if (newDate) {
                          // Mettre à jour l'événement avec la nouvelle date
                          const newStart = new Date(newDate);
                          event.setStart(newStart);
                          event.setEnd(new Date(newStart.getTime() + 3600000)); // Par exemple, ajouter 1h comme durée
                          console.log("newStart:", newStart);
                          console.log("disponibiliteId:", disponibiliteId);
                          // Assurez-vous de corriger l'heure selon le fuseau horaire local si nécessaire
                          const localDate = new Date(
                            newStart.getTime() -
                              newStart.getTimezoneOffset() * 60000
                          ); // Ajuster avec le décalage horaire

                          // Récupérer l'heure locale ajustée
                          const newLocalTime = localDate
                            .toISOString()
                            .split("T")[1]
                            .split("Z")[0]
                            .slice(0, 8); // Récupérer l'heure au format "HH:mm:ss"

                          // Préparer la nouvelle date pour la requête
                          const newDateForDb = localDate
                            .toISOString()
                            .split("T")[0]; // Formater la date sans l'heure

                          // Log des informations pour vérifier
                          console.log("newStart:", newStart); // Afficher la date initiale
                          console.log("newLocalTime:", newLocalTime); // Afficher l'heure locale ajustée
                          console.log("newDateForDb:", newDateForDb); // Afficher la date san
                          // Avant d'envoyer la requête, vérifie que tout est là
                          if (!newStart || !event.id) {
                            Swal.fire(
                              "Erreur",
                              "Données manquantes pour la mise à jour de l'événement.",
                              "error"
                            );
                            return; // Empêche l'envoi de la requête si des données manquent
                          }

                          // Envoi de la mise à jour à la base de données via AJAX
                          fetch("index.php?action=update_event", {
                            method: "POST",
                            headers: {
                              "Content-Type": "application/json",
                            },
                            body: JSON.stringify({
                              event_id: disponibiliteId, // Assurer que c'est bien event_id
                              new_date: newDateForDb, // Date sans l'heure
                              new_time: newLocalTime, // Heure séparée
                              type: prestationType, // Type de prestation récupéré
                              email: clientEmail, // Email du client récupéré
                              reason: reason, // Envoyer la raison avec les autres informations
                            }),
                          })
                            .then((response) => response.json())
                            .then((data) => {
                              console.log(data); // Ajoute ceci pour vérifier la réponse
                              if (data.status === "success") {
                                calendar.refetchEvents();
                                Swal.fire(
                                  "L'événement a été déplacé avec succès !"
                                );
                              } else {
                                Swal.fire(
                                  "Erreur",
                                  "Impossible de déplacer l'événement.",
                                  "error"
                                );
                              }
                            })
                            .catch((error) => {
                              console.error("Erreur de mise à jour :", error);
                              Swal.fire(
                                "Erreur lors de la mise à jour de l'événement"
                              );
                            });
                        }
                      }
                    });
                  });

                // Gestion du bouton supprimer l'événement
                document
                  .getElementById("deleteEvent")
                  .addEventListener("click", function () {
                    // Confirmation avant suppression
                    Swal.fire({
                      text: "Êtes-vous sûr de vouloir supprimer cet événement ?",
                      icon: "warning",
                      showCancelButton: true,
                      confirmButtonText: "Oui, supprimer",
                      cancelButtonText: "Non, annuler",
                      customClass: {
                        confirmButton: "btn btn-danger",
                        cancelButton: "btn btn-active-light",
                      },
                    }).then((result) => {
                      if (result.isConfirmed) {
                        // Supprimer l'événement de la base de données via AJAX
                        fetch("index.php?action=delete_event", {
                          method: "POST",
                          headers: {
                            "Content-Type": "application/json",
                          },
                          body: JSON.stringify({
                            event_id: event.id,
                            type: prestationType, // Type de prestation récupéré
                            email: clientEmail, // Email du client récupéré
                          }),
                        })
                          .then((response) => response.json())
                          .then((data) => {
                            console.log(data); // Ajoute ceci pour vérifier la réponse
                            // Supprimer l'événement du calendrier
                            event.remove();
                            Swal.fire("L'événement a été supprimé avec succès");
                          })
                          .catch((error) => {
                            console.error(
                              "Erreur lors de la suppression :",
                              error
                            );
                            Swal.fire(
                              "Erreur lors de la suppression de l'événement"
                            );
                          });
                      }
                    });
                  });
              },
            });
          },

          // Personnalisation des événements après leur rendu
          eventDidMount: function (info) {
            const currentDate = new Date();
            const eventStartDate = new Date(info.event.start);

            // // Si l'événement est passé, on le stylise avec un fond gris foncé
            // if (eventStartDate < currentDate) {
            //     info.el.style.backgroundColor = '#333'; // Gris foncé
            //     info.el.style.color = 'white'; // Texte blanc
            //     info.el.style.opacity = '0.7'; // Légèrement transparent
            // }

            // // Personnalisation pour le type de prestation (mariage/pink)
            // if (info.event.extendedProps.prestation_type === 'mariage') {
            //     info.el.style.borderColor = 'blue';
            // } else {
            //     info.el.style.borderColor = 'green';
            // }

            // Après le rendu, gérer le "Voir plus" pour limiter les événements visibles
            handleEventDisplay(info.el);
          },

          validRange: {
            start: null, // Les dates passées seront grisées
          },
        });

        calendar.render();
      })
      .catch((error) =>
        console.error("Erreur lors de la récupération des données :", error)
      );
  };

  return {
    init: function () {
      initCalendar();
    },
  };
})();

document.addEventListener("DOMContentLoaded", function () {
  CalendarApp.init();
});

document.addEventListener("DOMContentLoaded", function () {
  function updatePendingCommentsCount() {
    fetch("index.php?action=getPendingCommentsCount") // Cette URL est définie dans ton routage PHP
      .then((response) => response.json())
      .then((data) => {
        // Mettre à jour l'élément avec le nombre de commentaires en attente
        document.getElementById(
          "pending-comments-count"
        ).innerText = `Commentaires à approuver : ${data.count}`;
      })
      .catch((error) => {
        console.error(
          "Erreur lors de la récupération du nombre de commentaires en attente :",
          error
        );
      });
  }

  // Initialisation au chargement de la page
  updatePendingCommentsCount();
  document.querySelectorAll(".comment-box").forEach((commentBox) => {
    let approveBtn = commentBox.querySelector(".approve-btn");
    let deleteBtn = commentBox.querySelector(".delete-btn");

    if (approveBtn) {
      approveBtn.addEventListener("click", function () {
        let commentId = this.getAttribute("data-id");

        fetch(`index.php?action=approveComment&id=${commentId}`, {
          method: "POST",
        })
          .then((response) => response.json())
          .then((data) => {
            console.log(data.message);
            commentBox.remove(); // Supprime le commentaire validé
          })
          .catch((error) => {
            console.error(
              "Erreur lors de l'approbation du commentaire:",
              error
            );
          });
      });
    }

    if (deleteBtn) {
      deleteBtn.addEventListener("click", function () {
        let commentId = this.getAttribute("data-id");

        // Utilisation de SweetAlert pour confirmer la suppression
        Swal.fire({
          title: "Êtes-vous sûr ?",
          text: "Cette action est irréversible.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Oui, supprimer",
          cancelButtonText: "Non, annuler",
        }).then((result) => {
          if (result.isConfirmed) {
            fetch(`index.php?action=deleteComment&id=${commentId}`, {
              method: "POST",
            })
              .then((response) => response.json())
              .then((data) => {
                console.log(data.message);
                commentBox.remove(); // Supprime le commentaire supprimé
                Swal.fire(
                  "Supprimé !",
                  "Le commentaire a été supprimé.",
                  "success"
                );
              })
              .catch((error) => {
                console.error(
                  "Erreur lors de la suppression du commentaire:",
                  error
                );
                Swal.fire(
                  "Erreur !",
                  "Une erreur est survenue lors de la suppression.",
                  "error"
                );
              });
          } else {
            console.log("Suppression annulée.");
          }
        });
      });
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".comment-text").forEach((comment) => {
    const maxLength = 50;
    const fullText = comment.dataset.fullText?.trim();

    if (fullText && fullText.length > maxLength) {
      const truncatedText = fullText.substring(0, maxLength);
      comment.innerHTML = `${truncatedText} <span class="see-more">... Voir plus</span>`;
      comment.dataset.fullText = fullText;
      comment.dataset.truncatedText = truncatedText;

      comment.querySelector(".see-more").addEventListener("click", function () {
        toggleComment(this);
      });
    } else {
      comment.innerHTML = fullText;
    }
  });
});

function toggleComment(span) {
  const comment = span.parentElement;
  if (comment.classList.contains("expanded")) {
    comment.innerHTML = `${comment.dataset.truncatedText} <span class="see-more">... Voir plus</span>`;
    comment.classList.remove("expanded");
  } else {
    comment.innerHTML = `${comment.dataset.fullText} <span class="see-more"> Voir moins</span>`;
    comment.classList.add("expanded");
  }

  comment.querySelector(".see-more").addEventListener("click", function () {
    toggleComment(this);
  });
}

document.addEventListener("DOMContentLoaded", function () {
  let modal = document.getElementById("image-modal");
  let modalImg = document.getElementById("modal-img");
  let modalVideo = document.getElementById("modal-video");
  let closeBtn = document.querySelector(".close");

  // Quand on clique sur une image
  document.querySelectorAll(".media-single img").forEach((img) => {
    img.addEventListener("click", function () {
      modal.style.display = "flex";
      modalImg.style.display = "block"; // Afficher l'image
      modalVideo.style.display = "none"; // Cacher la vidéo

      // Réinitialiser la source de la vidéo au cas où
      modalVideo.src = "";

      modalImg.src = this.src; // Mettre l'image cliquée dans le modal
    });
  });

  // Quand on clique sur une vidéo
  document.querySelectorAll(".media-single video").forEach((video) => {
    video.addEventListener("click", function () {
      modal.style.display = "flex";
      modalImg.style.display = "none"; // Cacher l'image
      modalVideo.style.display = "block"; // Afficher la vidéo

      // Réinitialiser la source de l'image au cas où
      modalImg.src = "";

      modalVideo.src = this.src; // Mettre la vidéo cliquée dans le modal

      // Il est important de recharger la vidéo pour qu'elle s'affiche et se charge correctement
      modalVideo.load(); // Recharger la vidéo
      modalVideo.play(); // Lancer la lecture de la vidéo si tu veux qu'elle commence à jouer automatiquement
    });
  });

  function closeImageModal() {
    // Cibler le modal et la croix
    const modal = document.getElementById('image-modal');
    const closeButton = document.getElementById('closeModal');
    
    // Ajouter un event listener pour fermer le modal quand on clique sur la croix
    closeButton.addEventListener('click', function() {
        modal.style.display = 'none';  // Ferme le modal
    });
}

closeImageModal();

  // Quand on clique en dehors du modal, le fermer
  window.addEventListener("click", function (event) {
    if (event.target === modal) {
      modal.style.display = "none";
      modalImg.style.display = "none"; // Cacher l'image
      modalVideo.style.display = "none"; // Cacher la vidéo

      // Réinitialiser les sources quand le modal se ferme
      modalImg.src = "";
      modalVideo.src = "";
    }
  }
  );

  // Fermer le modal
  closeBtn.addEventListener("click", function () {
    modal.style.display = "none";
    modalImg.style.display = "none"; // Cacher l'image lorsque le modal se ferme
    modalVideo.style.display = "none"; // Cacher la vidéo lorsque le modal se ferme

    // Réinitialiser les sources quand le modal se ferme
    modalImg.src = "";
    modalVideo.src = "";
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const notifIcon = document.getElementById("notifIcon");
  const notifMenu = document.getElementById("notifMenu");
  const notifContent = document.getElementById("notifContent");
  const notifBadge = document.getElementById("notifBadge");

  // Fonction pour mettre à jour les notifications
  function updateNotifications() {
    fetch("index.php?action=getPendingCommentsCount")
      .then((response) => response.json())
      .then((data) => {
        let notifications = [];

        if (data.count > 0) {
          notifications.push(
            `Vous avez ${data.count} commentaire(s) en attente d'approbation.`
          );
        }

        if (notifications.length > 0) {
          notifContent.innerHTML = notifications
            .map((n) => `<p>${n}</p>`)
            .join("");
          notifBadge.innerText = notifications.length;
          notifBadge.style.display = "inline";
        } else {
          notifContent.innerHTML = "<p>Aucune notification en attente</p>";
          notifBadge.style.display = "none";
        }
      })
      .catch((error) =>
        console.error(
          "Erreur lors de la récupération des notifications :",
          error
        )
      );
  }

  // Mise à jour toutes les 10 secondes
  setInterval(updateNotifications, 10000);
  updateNotifications();

  // Toggle du menu
  notifIcon.addEventListener("click", function () {
    notifMenu.classList.toggle("show");
  });

  // Cacher si on clique ailleurs
  document.addEventListener("click", function (event) {
    if (
      !notifIcon.contains(event.target) &&
      !notifMenu.contains(event.target)
    ) {
      notifMenu.classList.remove("show");
    }
  });
});

document.getElementById("openModalBtn").addEventListener("click", function () {
  fetch("index.php?action=getAdminInfo")
    .then((response) => response.json())
    .then((data) => {
      document.getElementById("adminEmail").innerText = data.email;
      document.getElementById("adminModal").style.display = "block";
    })
    .catch((error) => console.error("Erreur :", error));
});

// Fermer le modal
document.querySelector(".close").addEventListener("click", function () {
  document.getElementById("adminModal").style.display = "none";
});

// Gérer les boutons
document
  .getElementById("changeEmailBtn")
  .addEventListener("click", function () {
    window.location.href = "index.php?action=changeEmail";
  });

document
  .getElementById("changePasswordBtn")
  .addEventListener("click", function () {
    window.location.href = "index.php?action=changePassword";
  });
