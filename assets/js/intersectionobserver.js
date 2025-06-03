// https://developer.mozilla.org/fr/docs/Web/API/IntersectionObserver 


//// example tiré du tuto youtube

// const observerIntersectionAnimation = () => {
//   const section = document.querySelectorAll("section");
//   const skills = document.querySelectorAll(".skills .bar");

//   section.forEach((section, index) => {
//     if(index === 0) return;
//     section.style.opacity = "0";
//     section.style.transition = "all 1.6s";
//   })

//   skills.forEach((elem, index) => {
//     elem.style.width = "0";
//     elem.style.transition = "all 1.6s";
//   })

//   let sectionObserver = new IntersectionObserver(function (entries, observer) {
//     entries.forEach(entry => {
//       if(entry.isIntersecting) {
//         let elem = entry.target;
//         elem.style.opacity = 1;
//       }
//     });
//   });

//   section.forEach(section => {
//     sectionObserver.observe(section)
//   })

//   let skillsObserver = new IntersectionObserver(function (entries, observer) {
//     entries.forEach(entry => {
//       if(entry.isIntersecting) {
//         let elem = entry.target;
//         console.log(elem);
//         elem.style.width = elem.dataset.width + "%"
//       }
//     });
//   });

//   skills.forEach(skill => {
//     skillsObserver.observe(skill)
//   })
// }

// observerIntersectionAnimation();



////// Event listener scroll pour voir...

// window.addEventListener("scroll", function (){
//     const scrollPos = window.scrollY; 
//     console.log(scrollPos); 
// })


// // Creation de l'Intersction Observer
// const observer = new IntersectionObserver(function (entries, observer) {
//   entries.forEach(entry => {
//     if (window.innerWidth < 1024) {
//         if (entry.isIntersecting) {
//             // Ajouter de la classe css visible pour afficher les éléments
//             entry.target.classList.add('visible');
//             // console.log(entries)
//             // console.log(plats);
//         }
//         else {
//         // Retirer la classe css visible
//         entry.target.classList.remove('visible');
//         }  
//     }
//   });
// }, {
//   threshold: 0.1 // Déclcencher lorsque 10% de l'élément est visible
// });

// // Observation des éléments plats
// plats.forEach(plat => observer.observe(plat));



// déclarer une variable pour sélectionner les bonnes classes css
const plats = document.querySelectorAll('.card-plats, .card-jour, .plats-du-jour h2, .plats-du-jour span');

// //Création de la fonction Intersction Observer
function initializeObserver() {
  // Enlever la classe non-visible au chargement
  plats.forEach(plat => plat.classList.remove('non-visible'));

  const observer = new IntersectionObserver(entries => {
    entries.forEach(function(entry) {
      if (window.innerWidth < 1024) {
        if (entry.isIntersecting) {
          entry.target.classList.remove('non-visible');
        } else {
          entry.target.classList.add('non-visible');
        }
      } else {
        entry.target.classList.remove('non-visible');
      }
    });
  }, {
    threshold: 0.1 
  });

  plats.forEach(function(plat) {
    observer.observe(plat);
  });

 
  
}

initializeObserver();

window.addEventListener('resize', function() {
  initializeObserver();
});

