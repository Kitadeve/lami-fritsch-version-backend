// À placer dans un fichier JS ou dans un <script> dans la page concernée
document.addEventListener("DOMContentLoaded", function () {
  fetch('./assets/php/get_suggestions.php')
    .then(response => response.json())
    .then(suggestions => {
      // Sélectionne le bon conteneur pour les suggestions dynamiques
      const cardsContainer = document.querySelector('.carte-suggestions .cards-plats-suggestions');
      if (!cardsContainer) return;
      cardsContainer.innerHTML = '';
      if (suggestions.length === 0) {
        cardsContainer.innerHTML = "<p>Aucune suggestion pour le moment.</p>";
        return;
      }
      suggestions.forEach(sugg => {
        const card = document.createElement('div');
        card.className = "card-plats";
        card.innerHTML = `
          <p class="plat">${sugg.nom}</p>
          <div class="trait-prix">
            <div class="trait-plats"></div>
            <p>${parseFloat(sugg.prix).toFixed(2)} €</p>
          </div>
        `;
        cardsContainer.appendChild(card);
      });
    });
});