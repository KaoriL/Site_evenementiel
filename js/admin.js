import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import frLocale from "@fullcalendar/core/locales/fr"; // Importer la langue française

document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("calendar");

  // Récupérer les créneaux réservés depuis l'API PHP avec fetch depuis index.php?action=disponibilites
  fetch("index.php?action=disponibilites")
    .then((response) => response.json())
    .then((data) => {
      const eventsByDate = {};

      // Traitement des disponibilités récupérées
      data.forEach((item) => {
        const key = `${item.date_disponible}-${item.horaire}`;

        let color = "green"; // Par défaut (disponible)
        if (item.est_reserve === 1) {
          color = item.prestation_type === "mariage" ? "blue" : "pink"; // Bleu pour mariage, rose pour autre prestation
        }

        if (item.est_reserve === 1) {
          // Grouper les événements par date pour gérer l'affichage limité
          if (!eventsByDate[item.date_disponible]) {
            eventsByDate[item.date_disponible] = [];
          }

          eventsByDate[item.date_disponible].push({
            title: `Réservé (${item.horaire})`,
            start: `${item.date_disponible}T${item.horaire}`,
            color: color,
            extendedProps: {
              prestation_type: item.prestation_type,
            },
          });
        }
      });

      // Convertir les événements en tableau, en limitant l'affichage à 2 par jour
      const events = [];
      Object.keys(eventsByDate).forEach((date) => {
        const eventList = eventsByDate[date];
        eventList.slice(0, 2).forEach((event) => events.push(event));

        if (eventList.length > 2) {
          // Ajouter un événement spécial pour signaler plus de réservations
          events.push({
            title: `+${eventList.length - 2} autres réservations`,
            start: `${date}T00:00:00`,
            color: "gray",
            extendedProps: {
              isMoreIndicator: true,
              fullEvents: eventList, // Stocker tous les événements pour affichage détaillé
            },
          });
        }
      });

      // Initialisation du calendrier avec les événements
      const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, interactionPlugin],
        locale: frLocale, // Définir la langue en français
        initialView: "dayGridMonth",
        events: events, // Utilisation des événements dynamiques
        eventClick: function (info) {
          if (info.event.extendedProps.isMoreIndicator) {
            // Afficher tous les événements d'un jour s'il y a plus de 2 réservations
            alert(
              "Réservations complètes :\n" +
                info.event.extendedProps.fullEvents
                  .map((e) => e.title)
                  .join("\n")
            );
          } else {
            alert("Vous avez cliqué sur un événement : " + info.event.title);
          }
        },
        // Personnalisation des événements
        eventContent: function (info) {
          const prestationType = info.event.extendedProps.prestation_type;
          const content = document.createElement("div");
          content.classList.add("event-content");
          content.innerHTML = info.event.title;

          if (prestationType === "mariage") {
            content.style.borderColor = "blue";
          } else {
            content.style.borderColor = "pink";
          }

          return { domNodes: [content] };
        },
        // Personnalisation des week-ends
        dayCellClassNames: function (info) {
          const day = info.date.getDay();
          if (day === 6 || day === 0) {
            return ["weekend"]; // Ajouter la classe pour les week-ends
          }
          return [];
        },
        // Personnalisation de l'affichage du jour actuel
        dayHeaderClassNames: function (info) {
          const today = new Date();
          if (
            info.date.getFullYear() === today.getFullYear() &&
            info.date.getMonth() === today.getMonth() &&
            info.date.getDate() === today.getDate()
          ) {
            return ["today"]; // Appliquer une classe CSS pour le jour actuel
          }
          return [];
        },
      });

      calendar.render();
    })
    .catch((error) => {
      console.error("Erreur lors de la récupération des données :", error);
    });
});
