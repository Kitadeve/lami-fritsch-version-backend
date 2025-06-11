  window.addEventListener("DOMContentLoaded", () => {
    const platsDuJour = JSON.parse(localStorage.getItem("platsDuJour") || "{}");

    document.querySelectorAll(".card-jour").forEach(card => {
      const jourEl = card.querySelector("h3.jour");
      const jour = jourEl.textContent.trim();
      
      if (platsDuJour[jour]) {
        const { entree, plat } = platsDuJour[jour];
        const frame = card.querySelector(".frame");
        frame.innerHTML = `
          <div class="entree">${entree}</div>
          <div class="trait"></div>
          <p class="plat">${plat}</p>
        `;
      }
    });
  });

// version en fetch, pour chercher le menu écrit en dur dans le json et similer la recherche dans une bdd//

// fetch('./assets/json/menus-jour.json')
//   .then(response => response.json())
//   .then(data => {
//     afficherPlatsDuJour(data.platsDuJour);
//   });


// function afficherPlatsDuJour(plats) {
//   const container = document.querySelector('.cards-pdj');
//   container.innerHTML = ''; // Vide les anciens plats

//   plats.forEach(plat => {
//     container.innerHTML += `
//       <div class="card-jour">
//         <h3 class="jour">${plat.jour}</h3>
//         <div class="frame">
//           <div class="entree">${plat.entree}</div>
//           <div class="trait"></div>
//           <p class="plat">${plat.plat}</p>
//         </div>
//       </div>
//     `;
//   });
// }

