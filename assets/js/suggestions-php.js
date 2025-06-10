document.addEventListener("DOMContentLoaded", function () {
  fetch('/lami-fritsch-version-backend/assets/php/get_suggestions.php')
    .then(response => response.json())
    .then(suggestions => {
      if (suggestions.error) {
        console.log(suggestions.error);
        return;
      }
      // Sélectionne le bon conteneur pour les suggestions dynamiques
      const cardsContainer = document.querySelector('.suggestions .cards-plats-suggestions');
      if (!cardsContainer) return;
      cardsContainer.innerHTML = '';
      if (suggestions.length === 0) {
        cardsContainer.innerHTML = "<p>Aucune suggestion pour le moment.</p>";
        return;
      }
      suggestions.forEach(sugg => {
        const card = document.createElement('div');
        const prix = parseFloat(sugg.prix).toFixed(2).replace('.', ',');
        card.className = "card-plats";
        card.innerHTML = `
          <p class="plat">${sugg.nom}</p>
          <div class="trait-prix">
            <div class="trait-plats"></div>
            <p>${prix} €</p>
          </div>
        `;
        cardsContainer.appendChild(card);
      });
    })
    .catch(error => {
      console.log("Erreur lors de la récupération des suggestions.", error);
    });
});