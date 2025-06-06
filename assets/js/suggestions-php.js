// À placer dans un fichier JS ou dans un <script> dans la page concernée
document.addEventListener("DOMContentLoaded", function () {
  fetch('./assets/php/get_suggestions.php')
    .then(response => response.json())
    .then(suggestions => {
      // Sélectionne le bon conteneur pour les suggestions dynamiques
      const cardsContainer = document.querySelector('.carte-suggestions .cards-plats-suggestions');
      const datalist = document.getElementById('suggestions-list'); // Assurez-vous que l'ID correspond à votre datalist
      if (!cardsContainer) return;
      cardsContainer.innerHTML = '';
      datalist.innerHTML = ''; // Vider le datalist avant d'y ajouter des options
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

        // Ajout des options au datalist
        const option = document.createElement('option');
        option.value = sugg.nom;
        if (sugg.visible == 0) {
          option.textContent = `${sugg.nom} (masquée)`;
        }
        datalist.appendChild(option);
      });
    });
});