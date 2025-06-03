function filtres() {
    const platsSection = document.querySelector(".plats-filtre");
    const tartesSection = document.querySelector(".tartes-flambees");
    const dessertsSection = document.querySelector(".desserts");
    const filterTitles = document.querySelectorAll(".filtre button");

    // Fonction pour afficher la section sélectionnée et masquer les autres
    function showSection(sectionToShow, activeFilterClass) {
      // Masquer toutes les sections
      platsSection.classList.add("hide");
      tartesSection.classList.add("hide");
      dessertsSection.classList.add("hide");

      // Afficher la section sélectionnée
      sectionToShow.classList.remove("hide");

      // Retirer la classe active de tous les titres de filtre
      filterTitles.forEach(title => title.classList.remove("active"));

      // Ajouter la classe active au titre de filtre cliqué
      const activeTitle = document.querySelector(`.${activeFilterClass}`);
      if (activeTitle) {
        activeTitle.classList.add("active");
      }
    }

    // Vérifie si la largeur de l'écran est supérieure ou égale à 1024px
    if (window.innerWidth >= 1024) {
      // Ajoute les écouteurs de clic aux titres de filtre
      document.querySelector(".filtre-plats").addEventListener("click", () => {
        showSection(platsSection, "filtre-plats");
      });

      document
        .querySelector(".filtre-tartes-flambees")
        .addEventListener("click", () => {
          showSection(tartesSection, "filtre-tartes-flambees");
        });

      document
        .querySelector(".filtre-desserts")
        .addEventListener("click", () => {
          showSection(dessertsSection, "filtre-desserts");
        });

      // Affiche la section des plats par défaut
      showSection(platsSection, "filtre-plats");
    } else {
      // Sur mobile, toutes les sections sont visibles
      platsSection.classList.remove("hide");
      tartesSection.classList.remove("hide");
      dessertsSection.classList.remove("hide");
    }
  };

  // Accessibilité clavier : active le filtre avec Entrée ou Espace
  // document.querySelectorAll(".filtre h3").forEach((element) => {
  //   element.addEventListener("keydown", (event) => {
  //     if (event.key === "Enter" || event.key === " ") {
  //       event.preventDefault(); // Empêche le défilement de la page si "Espace" est pressé
  //       element.click(); // Simule un clic sur l'élément
  //     }
  //   });
  // });

  document.addEventListener("DOMContentLoaded", filtres)
  window.addEventListener("resize", filtres)
