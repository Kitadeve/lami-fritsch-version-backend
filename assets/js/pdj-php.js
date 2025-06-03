fetch('./assets/php/get_menus_du_jour.php')
  .then(res => res.json())
  .then(menus => {
    document.querySelectorAll('.card-jour').forEach(card => {
      const jour = card.querySelector('h3.jour').textContent.trim();
      const menu = menus.find(m => m.jour === jour);
      if (menu) {
        card.querySelector('.frame').innerHTML = `
          <div class="entree">${menu.entree}</div>
          <div class="trait"></div>
          <p class="plat">${menu.plat}</p>
        `;
      }
    });
  });