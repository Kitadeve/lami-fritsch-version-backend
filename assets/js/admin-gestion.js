
document.addEventListener('DOMContentLoaded', function() {
  // Confirmation pour la suppression
  document.querySelectorAll('button[name="action"][value="supprimer"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
      if (!confirm('Confirmer la suppression ? Cette action est irréversible.')) {
        e.preventDefault();
      }
    });
  });

  // Confirmation pour la modification
  document.querySelectorAll('button[name="action"][value="modifier"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
      if (!confirm('Confirmer la modification ?')) {
        e.preventDefault();
      }
    });
  });

  //confirmation pour la visibilité
  const btnShow = document.querySelectorAll("button[name='action'][value='afficher']");
  const btnHide = document.querySelectorAll("button[name='action'][value='masquer']");
  const btnOui = document.querySelector("button[name='action'][value='afficher']");
  const btnNon = document.querySelectorAll("button[name='action'][value='non']");
  const confirmation = document.querySelector(".confirm-display");
  const confirmTexte = document.querySelector(".texte-confirm");

  btnShow.forEach(btn => {
    btn.addEventListener("click", function (e) {
      if (!confirm("Afficher la suggestion. Confirmer ?")) {
        e.preventDefault();
      }
      // e.preventDefault();
      // confirmation.classList.add("show");
      // confirmTexte.innerHTML = "Afficher la suggestion. Confirmer ?";
    })
  });

  // btnOui.addEventListener("click", function () {
  //   confirmation.classList.remove("show");
  // })

  btnHide.forEach(btn => {
    btn.addEventListener("click", function (e) {
      if (!confirm("Masquer la suggestion. Confirmer ?")) {
        e.preventDefault();
      }
    })
  });

  // Auto-resize des textareas
  document.querySelectorAll('.auto-resize').forEach(function(textarea) {
    function resize() {
      textarea.style.height = 'auto';
      textarea.style.height = textarea.scrollHeight + 'px';
    }
    textarea.addEventListener('input', resize);
    resize(); // Redimensionne au chargement
  });
});