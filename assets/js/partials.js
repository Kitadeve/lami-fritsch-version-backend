// // Chargement du header
function loadHeader() {
  if (document.querySelector("header")) {
    console.warn("Header already exists. Skipping dynamic load.");
    return;
  }
  const headerPlaceholder = document.createElement("div");
  headerPlaceholder.id = "header-placeholder";
  document.body.insertBefore(headerPlaceholder, document.body.firstChild);

  fetch("./assets/partials/header.html")
    .then(response => response.text())
    .then(data => {
      document.getElementById("header-placeholder").innerHTML = data;
      const header = document.querySelector("header");
      //Expression ternaire, si le header existe (si il est chargé), img prend la valeur du querySelector, sinon, elle est null
      // const img = header ? header.querySelector("img") : null;
      const img = header.querySelector("img")
      
      // if (img) {
      
        if (img.complete) {
          // Image déjà chargée
          menuMobile();
          updateHeaderHeight();
          updateBurgerHeight();
        } else {
          img.addEventListener("load", () => {
            menuMobile();
            setTimeout(() => { updateHeaderHeight(); }, 100);
            // updateHeaderHeight();
            updateBurgerHeight();
          });
          img.addEventListener("error", () => {
            menuMobile();
            updateHeaderHeight();
            updateBurgerHeight();
          });
        }

      // } 
      // else {
      //   // Pas d'image, on lance tout de suite
      //   menuMobile();
      //   updateHeaderHeight();
      //   updateBurgerHeight();
      // }
    })
    .catch((error) => console.error("Erreur lors du chargement du header :", error));
}

// Chargement du footer
function loadFooter() {
  const footerPlaceholder = document.createElement("div");
  footerPlaceholder.id = "footer-placeholder";
  document.body.appendChild(footerPlaceholder);

  fetch('./assets/partials/footer.html')
    .then(response => response.text())
    .then(data => {
      document.getElementById('footer-placeholder').innerHTML = data;
    })
    .catch(error => console.error("Erreur lors du chargement du footer :", error));
}

window.addEventListener("DOMContentLoaded", () => {
    loadHeader();
    loadFooter();
    }
);

