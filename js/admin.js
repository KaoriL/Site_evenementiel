import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import frLocale from "@fullcalendar/core/locales/fr";
import Swal from "sweetalert2";

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
            title: `Réservé (${item.horaire})`,
            start: `${item.date_disponible}T${item.horaire}`,
            end: `${item.date_disponible}`,
            color: item.color,
            id: item.disponibilite_id, // Utiliser l'ID de la disponibilité ici
            extendedProps: {
              prestation_type: item.prestation_type,
              prestation_id: item.id, // Ajout de l'ID de la prestation
              disponibilite_id: item.disponibilite_id,
              client_email: item.client_email,
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
          dayMaxEvents: true, // Limiter à un certain nombre d'événements visibles
          events: events,

          // Event click pour afficher les détails
          eventClick: function (info) {
            // Récupérer les informations de l'événement
            const event = info.event;
            const prestationId = event.extendedProps.prestation_id; // Ajout de l'ID de la prestation
            const disponibiliteId = event.id;
            const eventName = event.title;
            const eventStart = event.start.toLocaleString(); // Afficher la date et l'heure de l'événement
            const eventEnd = event.end
              ? event.end.toLocaleString()
              : "Non spécifiée";
            const prestationType = event.extendedProps.prestation_type; // Type de prestation
            const clientEmail = event.extendedProps.client_email; // Email du client pour l'envoi de mail

            // Afficher une fenêtre modale avec les détails de l'événement et les boutons
            Swal.fire({
              title: eventName,
              html: `<div><strong>ID de la prestation:</strong> ${prestationId}</div>
            <div><strong>ID de la disponibilité:</strong> ${disponibiliteId}</div>
                                <div><strong>Nom de l'événement:</strong> ${eventName}</div>
                                <div><strong>Start:</strong> ${eventStart}</div>
                                <div><strong>End:</strong> ${eventEnd}</div>
                                <div><strong>Type de prestation:</strong> ${prestationType}</div>
                                <div class="mt-3">
                                    <button id="moveEvent" class="btn btn-info mr-2">Déplacer</button>
                                    <button id="deleteEvent" class="btn btn-danger">Supprimer</button>
                                </div>
                            `,
              showCancelButton: false,
              focusConfirm: false,
              customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-active-light",
              },
              didOpen: function () {
                // Gestion des actions des boutons après l'ouverture de la fenêtre
                document
                  .getElementById("moveEvent")
                  .addEventListener("click", function () {
                    // Ouvrir un autre Swal pour déplacer l'événement avec un Flatpickr
                    Swal.fire({
                      title: "Choisissez une nouvelle date et heure",
                      html: `<input type="text" id="flatpickr" class="form-control" />`,
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
                            .split('T')[1].split('Z')[0].slice(0, 8); // Récupérer l'heure au format "HH:mm:ss"

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
                          }),
                        })
                          .then((response) => response.json())
                          .then((data) => {
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
