


// Gestion du menu mobile
function menuMobile() {
  const btn = document.querySelector(".burger");
  const header = document.querySelector("header");
  const links = document.querySelectorAll(".burger-overlap a");
  const body = document.body;

  
  btn.addEventListener("click", function() {
    header.classList.toggle("show-overlap");
    body.classList.toggle("no-scroll");
  });

  links.forEach(link => {
    link.addEventListener("click", function() {
      header.classList.remove("show-overlap");
      body.classList.remove("no-scroll");
    });
  });
}

// Mise à jour de la hauteur du menu burger
function updateBurgerHeight() {
  const headerHeight = document.querySelector("header").offsetHeight;
  const burgerOverlap = document.querySelector(".burger-overlap");
  const burgerHeight = window.innerHeight - headerHeight;
  burgerOverlap.style.height = `${burgerHeight}px`;
}

// Mise à jour de la hauteur du header
function updateHeaderHeight() {
  const header = document.querySelector("header");
  const root = document.documentElement;
  root.style.setProperty("--header-height", `${header.offsetHeight}px`);
}

// window.addEventListener("DOMcontentloaded", function() {
//   updateHeaderHeight();
//   updateBurgerHeight();
//   menuMobile();
// });

window.addEventListener("resize", function() {
  updateHeaderHeight();
  updateBurgerHeight();
});