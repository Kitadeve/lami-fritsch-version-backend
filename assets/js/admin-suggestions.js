document.addEventListener("DOMContentLoaded", function () {
  const suggestionForm = document.getElementById("suggestions-form");
  const suggestionInput = document.getElementById("suggestion");
  const prixInput = document.getElementById("prix");
  const suggestionMsg = document.querySelector(".message-suggestions");

  suggestionForm.addEventListener("submit", function (e) {
    e.preventDefault(); // Toujours empêcher l'envoi classique

    let valid = false;
    suggestionMsg.textContent = "";
    suggestionInput.classList.remove("input-error");
    prixInput.classList.remove("input-error");

    // Vérifie que les deux champs sont remplis
    if (suggestionInput.value.trim() && prixInput.value.trim()) {
      // Vérifie le format du prix
      const prixRegex = /^\d{1,2}[.,]\d{2}$/;
      if (prixRegex.test(prixInput.value.trim())) {
        valid = true;
        suggestionMsg.classList.remove("message-error");
      } else {
        suggestionMsg.textContent = "❌ Format de prix invalide";
        suggestionMsg.classList.add("message-error");
        prixInput.classList.add("input-error");
      }
    } else {
      suggestionMsg.textContent = "❌ Merci de remplir un plat ET un prix.";
      suggestionMsg.classList.add("message-error");
      if (!suggestionInput.value.trim()) suggestionInput.classList.add("input-error");
      if (!prixInput.value.trim()) prixInput.classList.add("input-error");
    }

    if (valid) {
      // Envoi AJAX si tout est OK
      const formData = new FormData(suggestionForm);
      console.log(suggestionForm);
      

      fetch("../php/admin_save_suggestion.php", {
        method: "POST",
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            suggestionMsg.textContent = "✅ " + data.message;
            suggestionMsg.classList.remove("message-error");
            suggestionMsg.classList.add("message-success");
            suggestionInput.value = "";
            prixInput.value = "";
            setTimeout(() => {
              suggestionMsg.textContent = "";
              suggestionMsg.classList.remove("message-success");
            }, 2000);
          } else {
            suggestionMsg.textContent = "❌ " + data.message;
            suggestionMsg.classList.remove("message-success");
            suggestionMsg.classList.add("message-error");
          }
        })
        .catch(() => {
          suggestionMsg.textContent = "❌ Une erreur est survenue.";
          suggestionMsg.classList.remove("message-success");
          suggestionMsg.classList.add("message-error");
        });
    }
  });

  // Remplir le datalist des suggestions
  fetch('../php/admin_get_suggestions.php')
    .then(response => response.json())
    .then(suggestions => {
      const datalist = document.getElementById('suggestions-list-datalist');
      if (!datalist) return;
      datalist.innerHTML = '';
      suggestions.forEach(sugg => {
        // On encode le nom pour la sécurité
        const option = document.createElement('option');
        option.value = sugg.nom;
        option.textContent = `${sugg.nom} (${parseFloat(sugg.prix).toFixed(2)} €)`;
        datalist.appendChild(option);
      });
    });

  // Auto-remplir le prix si suggestion connue
  suggestionInput.addEventListener("input", function () {
    fetch('../php/admin_get_suggestions.php')
      .then(response => response.json())
      .then(suggestions => {
        const found = suggestions.find(s => s.nom === suggestionInput.value);
        if (found) {
          prixInput.value = parseFloat(found.prix).toFixed(2);
        }
      });
  });
});