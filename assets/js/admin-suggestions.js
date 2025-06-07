document.addEventListener("DOMContentLoaded", () => {
  initSuggestionForm();
  loadSuggestions();
});

function initSuggestionForm() {
  const form = document.getElementById("suggestions-form");
  form.addEventListener("submit", handleFormSubmit);

  const suggestionInput = document.getElementById("suggestion");
  suggestionInput.addEventListener("input", () => {
    fillPrixIfKnown();
    updateMiniBtn();
  });
}

function handleFormSubmit(e) {
  e.preventDefault();

  const suggestionInput = document.getElementById("suggestion");
  const prixInput = document.getElementById("prix");
  const message = document.querySelector(".message-suggestions");
  const form = e.target;

  clearErrors(suggestionInput, prixInput, message);

  const isValid = validateForm(suggestionInput, prixInput, message);

  if (!isValid) return;

  const formData = new FormData(form);

  fetch("../php/admin_save_suggestion.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(data => handleResponse(data, message, suggestionInput, prixInput))
    .catch(() => {
      message.textContent = "❌ Une erreur est survenue.";
      message.classList.add("message-error");
    });
}

function validateForm(suggestionInput, prixInput, message) {
  const suggestion = suggestionInput.value.trim();
  const prix = prixInput.value.trim();
  const prixRegex = /^\d{1,2}[.,]\d{2}$/;

  if (!suggestion || !prix) {
    message.textContent = "❌ Merci de remplir un plat ET un prix.";
    message.classList.add("message-error");
    if (!suggestion) suggestionInput.classList.add("input-error");
    if (!prix) prixInput.classList.add("input-error");
    return false;
  }

  if (!prixRegex.test(prix)) {
    message.textContent = "❌ Format de prix invalide";
    message.classList.add("message-error");
    prixInput.classList.add("input-error");
    return false;
  }

  return true;
}

function clearErrors(suggestionInput, prixInput, message) {
  suggestionInput.classList.remove("input-error");
  prixInput.classList.remove("input-error");
  message.textContent = "";
  message.classList.remove("message-error", "message-success");
}

function handleResponse(data, message, suggestionInput, prixInput) {
  if (data.success) {
    message.textContent = "✅ " + data.message;
    message.classList.add("message-success");
    suggestionInput.value = "";
    prixInput.value = "";
    setTimeout(() => message.textContent = "", 2000);
    loadSuggestions(); // recharge les suggestions après ajout
  } else {
    message.textContent = "❌ " + data.message;
    message.classList.add("message-error");
  }
}

// suggestions globales
let allSuggestions = [];

function loadSuggestions() {
  fetch('../php/admin_get_suggestions.php')
    .then(res => res.json())
    .then(data => {
      allSuggestions = data;
      populateDatalist(data);
    });
}

function populateDatalist(suggestions) {
  const datalist = document.getElementById('suggestions-list-datalist');
  if (!datalist) return;
  datalist.innerHTML = '';

  suggestions.forEach(sugg => {
    const option = document.createElement('option');
    option.value = sugg.nom;
    option.textContent = `${sugg.nom} (${parseFloat(sugg.prix).toFixed(2)} €)`;
    datalist.appendChild(option);
  });
}

function fillPrixIfKnown() {
  const suggestionInput = document.getElementById("suggestion");
  const prixInput = document.getElementById("prix");
  const found = allSuggestions.find(s => s.nom === suggestionInput.value);
  if (found) {
    prixInput.value = parseFloat(found.prix).toFixed(2);
  }
}

function updateMiniBtn() {
  const suggestionInput = document.getElementById("suggestion");
  const container = document.getElementById("suggestion-btn-container");
  container.innerHTML = "";

  const found = allSuggestions.find(s => s.nom === suggestionInput.value);
  if (!found) return;

  const btn = document.createElement("button");
  btn.type = "button";
  btn.className = "mini-btn";
  btn.title = found.visible ? "Masquer" : "Afficher";
  btn.innerHTML = found.visible ? "🚫" : "👁";

  btn.addEventListener("click", () => {
    toggleVisibility(found);
  });

  container.appendChild(btn);
}

function toggleVisibility(suggestion) {
  fetch('/lami-fritsch-version-backend/assets/php/admin_toggle_suggestion_visibility.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      id: suggestion.id,
      visible: suggestion.visible ? 0 : 1
    })
  })
    .then(res => res.json())
    .then(data => {
      if (!data.success) {
        alert(data.message || "Erreur lors de la modification.");
        return;
      }
      suggestion.visible = suggestion.visible ? 0 : 1;
      updateMiniBtn();
    })
    .catch(error => {
      alert("Erreur technique ou session expirée.");
      console.error(error);
    });
}
