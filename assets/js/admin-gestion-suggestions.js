document.addEventListener('DOMContentLoaded', function() {
  // Confirmation pour la suppression
  document.querySelectorAll('.suggestions-table button[name="action"][value="supprimer"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
      if (!confirm('Confirmer la suppression ? Cette action est irréversible.')) {
        e.preventDefault();
      }
    });
  });

  // Confirmation pour la modification
  document.querySelectorAll('.suggestions-table button[name="action"][value="modifier"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
      if (!confirm('Confirmer la modification ?')) {
        e.preventDefault();
      }
    });
  });

  //confirmation pour la visibilité
  const btnShow = document.querySelectorAll('.suggestions-table button[name="action"][value="afficher"]');
  const btnHide = document.querySelectorAll('.suggestions-table button[name="action"][value="masquer"]');
  const btnOui = document.querySelector("button[name='action'][value='oui']");
  const btnNon = document.querySelectorAll("button[name='action'][value='non']");
  const confirmation = document.querySelector(".confirm-display");
  const confirmTexte = document.querySelector(".texte-confirm");

  let pendingAction = null;
  let pendingId = null;
  let lastClickedButton = null;

  //Gestion du bouton afficher
  btnShow.forEach(btn => {
    btn.addEventListener("click", function (e) {
   
      e.preventDefault();

      const form = btn.closest("form");
      const id = form.querySelector("input[name='id']").value;

      pendingAction = "afficher";
      pendingId = id;
      lastClickedButton = btn;

      confirmation.classList.add("show");
      confirmTexte.textContent = "Afficher la suggestion. Confirmer ?";
    })
  });

 
  //Gestion du bouton masquer
  btnHide.forEach(btn => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      console.log("clique");
      
      const form = btn.closest("form");
      const id = form.querySelector("input[name='id']").value;

      pendingAction = "masquer";
      pendingId = id;
      lastClickedButton = btn;

      confirmation.classList.add("show");
      confirmTexte.textContent = "Masquer la suggestion. Confirmer ?";
    })
  });

    //Gestion du bouton oui de la modale
    btnOui.addEventListener("click", function() {
      if (!pendingAction || !pendingId) {
        console.log("Pas d'action ni d'id");
        return;
      }

    //Récupération du fichier php pour gérer la modif dans la bdd, en AJAX
      fetch("./admin_gestion_suggestions.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `action=${pendingAction}&id=${pendingId}`
      })
      .then(response => {
        if (!response.ok) throw new Error("Erreur réseau");
        return response.text();
      })
      .then(() => {

        const form = lastClickedButton.closest("form");
        const currentButton = lastClickedButton;

        currentButton.remove();

        const newButton = document.createElement("button");
        newButton.classList.add("mini-btn");

        if (pendingAction === "afficher") {
          newButton.classList.add("hide");
          newButton.setAttribute("type", "submit");
          newButton.setAttribute("name", "action");
          newButton.setAttribute("value", "masquer");
          newButton.setAttribute("title", "Masquer");
          newButton.innerHTML = "🚫";
        } else if (pendingAction === "masquer") {
          newButton.classList.add("show");
          newButton.setAttribute("type", "submit");
          newButton.setAttribute("name", "action");
          newButton.setAttribute("value", "afficher");
          newButton.setAttribute("title", "Masquer");
          newButton.innerHTML = "👁";
        }

        const deleteButton = form.querySelector("button[name='action'][value='supprimer']");
        form.insertBefore(newButton, deleteButton);

        newButton.addEventListener("click", function (e) {
          e.preventDefault();
          const id = form.querySelector("input[name='id']").value;
          pendingId = id;
          pendingAction = newButton.value;

          confirmTexte.textContent = (pendingAction === 'afficher')
            ? "Afficher la suggestion. Confirmer ?"
            : "Masquer la suggestion. Confirmer ?";

          lastClickedButton = newButton;
          confirmation.classList.add("show");
        })

      })
      .catch(error => {
        console.error("Erreur AJAX :", error);
      })
      .finally (() => {
        confirmation.classList.remove("show");
        pendingAction = null;
        pendingId = null;
      });
    });

    //Gestion du bouton non
    btnNon.forEach(btn => {
      btn.addEventListener("click", function () {
        confirmation.classList.remove("show");
        pendingAction = null;
        pendingId = null;
      });
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

  const suggestionForms = document.querySelectorAll('.suggestion-form');
});