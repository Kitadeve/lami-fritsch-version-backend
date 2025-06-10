document.addEventListener("DOMContentLoaded", function () {
  fetch('/lami-fritsch-version-backend/assets/php/get_carte.php')
    .then(response => response.json())
    .then(carte => {
      if (carte.error) {
        console.log(carte.error);
        return;
      }
      // Sélectionne le bon conteneur pour les suggestions dynamiques
      const cardsContainer = document.querySelector('.carte .cards-plats-suggestions');
      if (!cardsContainer) return;
      cardsContainer.innerHTML = '';
      if (carte.length === 0) {
        cardsContainer.innerHTML = "<p>Aucun plat pour le moment.</p>";
        return;
      }

        // Sélectionne les bons conteneurs pour chaque catégorie
      const platsContainer = document.querySelector('.plats-filtre .cards-plats-suggestions');
      const tartesContainer = document.querySelector('.tartes-flambees .cards-plats-suggestions');
      const dessertsContainer = document.querySelector('.desserts .cards-plats-suggestions');

      // Vide les conteneurs
      platsContainer.innerHTML = '';
      tartesContainer.innerHTML = '';
      dessertsContainer.innerHTML = '';


    carte.forEach(elem => {
      const card = document.createElement('div');
      const prix = parseFloat(elem.prix).toFixed(2).replace('.', ',');
      card.className = "card-plats";
      card.innerHTML = `
        <p class="plat">${elem.nom}</p>
        ${elem.description ? `<p class="description">${elem.description}</p>` : ''}
        <div class="trait-prix">
          <div class="trait-plats"></div>
          <p>${prix} €</p>
        </div>
      `;
      if (elem.categorie === 'plats') platsContainer.appendChild(card);
      else if (elem.categorie === 'tartes_flambees') tartesContainer.appendChild(card);
      else if (elem.categorie === 'desserts') dessertsContainer.appendChild(card);
      });
    })
    .catch(error => {
      console.log("Erreur lors de la récupération des plats.", error);
    });
});