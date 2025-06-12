document.addEventListener('DOMContentLoaded', function() {
  // Confirmation modal elements (reuse the same as suggestions)
  const btnOui = document.querySelector("button[name='action'][value='oui']");
  const btnNon = document.querySelectorAll("button[name='action'][value='non']");
  const confirmation = document.querySelector(".confirm-display");
  const confirmTexte = document.querySelector(".texte-confirm");

  let pendingAction = null;
  let pendingId = null;
  let lastClickedButton = null;

  // Confirm before modifying or deleting
  document.querySelectorAll('.carte-form button[name="action"][value="modifier"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
      if (!confirm('Confirmer la modification du plat ?')) {
        e.preventDefault();
      }
    });
  });
  document.querySelectorAll('.carte-form button[name="action"][value="supprimer"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
      if (!confirm('Confirmer la suppression du plat ? Cette action est irréversible.')) {
        e.preventDefault();
      }
    });
  });

  // --- Toggle visibility with confirmation and AJAX ---
  const btnShow = document.querySelectorAll('#show');
  const btnHide = document.querySelectorAll('#hide')

  btnShow.forEach(btn => {
    btn.addEventListener("click", function (e) {
        console.log("clique");
        
      e.preventDefault();
    //   if (!confirm("Afficher ce plat ?")) return;
      toggleCarteVisibility(btn, "afficher");
    });
  });

  btnHide.forEach(btn => {
    btn.addEventListener("click", function (e) {
        console.log("clique aussi");
        
      e.preventDefault();
    //   if (!confirm("Masquer ce plat ?")) return;
      toggleCarteVisibility(btn, "masquer");
    });
  });

  function toggleCarteVisibility(btn, action) {
    const form = btn.closest("form");
    const id = form.querySelector("input[name='id']").value;

    fetch("./admin_gestion_carte.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `action=${action}&id=${id}`
    })
    .then(response => {
      if (!response.ok) throw new Error("Erreur réseau");
      return response.text();
    })
    .then(() => {
      // Optionally, update the UI (swap button)
      if (action === "afficher") {
        btn.classList.remove("show");
        btn.classList.add("hide");
        btn.value = "masquer";
        btn.title = "Masquer";
        btn.innerHTML = "&#128683;";
      } else {
        btn.classList.remove("hide");
        btn.classList.add("show");
        btn.value = "afficher";
        btn.title = "Afficher";
        btn.innerHTML = "&#128065;";
      }
    })
    .catch(error => {
      alert("Erreur lors du changement de visibilité.");
      console.error(error);
    });
  }

  // Gestion du bouton afficher
  document.querySelectorAll('.carte-form button[name="action"][value="afficher"]').forEach(btn => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const form = btn.closest("form");
      const id = form.querySelector("input[name='id']").value;

      pendingAction = "afficher";
      pendingId = id;
      lastClickedButton = btn;

      confirmation.classList.add("show");
      confirmTexte.textContent = "Afficher ce plat sur la carte. Confirmer ?";
    });
  });

  // Gestion du bouton masquer
  document.querySelectorAll('.carte-form button[name="action"][value="masquer"]').forEach(btn => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const form = btn.closest("form");
      const id = form.querySelector("input[name='id']").value;

      pendingAction = "masquer";
      pendingId = id;
      lastClickedButton = btn;

      confirmation.classList.add("show");
      confirmTexte.textContent = "Masquer ce plat de la carte. Confirmer ?";
    });
  });

  // Gestion du bouton oui de la modale
  btnOui.addEventListener("click", function() {
    if (!pendingAction || !pendingId) {
      console.log("Pas d'action ni d'id");
      return;
    }

    fetch("./admin_gestion_carte.php", {
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
      // Swap the button in the UI
      const form = lastClickedButton.closest("form");
      const currentButton = lastClickedButton;

      currentButton.remove();

      const newButton = document.createElement("button");
      newButton.classList.add("mini-btn");

      if (pendingAction === "afficher") {
        newButton.classList.add("hide");
        newButton.setAttribute("type", "button");
        newButton.setAttribute("name", "action");
        newButton.setAttribute("value", "masquer");
        newButton.setAttribute("title", "Masquer");
        newButton.innerHTML = "🚫";
        confirmTexte.textContent = "";
        confirmation.classList.remove("show")

      } else if (pendingAction === "masquer") {
        newButton.classList.add("show");
        newButton.setAttribute("type", "button");
        newButton.setAttribute("name", "action");
        newButton.setAttribute("value", "afficher");
        newButton.setAttribute("title", "Afficher");
        newButton.innerHTML = "👁";
        confirmTexte.textContent = "";
        confirmation.classList.remove("show")
      }

      // Insert the new button before the delete button
      const deleteButton = form.querySelector("button[name='action'][value='supprimer']");
      form.insertBefore(newButton, deleteButton);

      // Re-bind the event to the new button
      newButton.addEventListener("click", function (e) {
        e.preventDefault();
        const id = form.querySelector("input[name='id']").value;
        pendingId = id;
        pendingAction = newButton.value;

        confirmTexte.textContent = (pendingAction === 'afficher')
          ? "Afficher ce plat sur la carte. Confirmer ?"
          : "Masquer ce plat de la carte. Confirmer ?";

        lastClickedButton = newButton;
        confirmation.classList.add("show");
      });
    })
    .catch(error => {
      console.error("Erreur AJAX :", error);
      confirmTexte.textContent = "Erreur lors du changement de visibilité.";
    })
    .finally (() => {
      setTimeout(() => {
        confirmation.classList.remove("show");
        pendingAction = null;
        pendingId = null;
      }, 1200);
    });
  });

  // Gestion du bouton non
  btnNon.forEach(btn => {
    btn.addEventListener("click", function () {
      confirmation.classList.remove("show");
      pendingAction = null;
      pendingId = null;
    });
  });

  // Auto-resize for all textareas
  document.querySelectorAll('.carte-form textarea').forEach(function(textarea) {
    function resize() {
      textarea.style.height = 'auto';
      textarea.style.height = textarea.scrollHeight + 'px';
    }
    textarea.addEventListener('input', resize);
    resize();
  });

  // Ajout dynamique de description
  document.querySelectorAll('.carte-form').forEach(form => {
    const addBtn = form.querySelector('.add-description');
    if (addBtn) {
      addBtn.addEventListener('click', function() {
        // Crée un nouveau textarea et un input hidden vide pour l'id
        const textarea = document.createElement('textarea');
        textarea.className = 'gestion-input';
        textarea.name = 'descriptions[]';
        textarea.style.minWidth = '120px';

        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'description_ids[]';
        hidden.value = '';

        // Insère avant le bouton "+"
        form.insertBefore(textarea, addBtn);
        form.insertBefore(hidden, addBtn);

        // Optionnel : auto-focus
        textarea.focus();
      });
    }
  });
});