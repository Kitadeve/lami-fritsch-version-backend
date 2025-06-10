document.addEventListener("DOMContentLoaded", () => {
  initCarteForm();
  loadCarte();
});

function initCarteForm() {
  const form = document.getElementById("carte-form");
  form.addEventListener("submit", handleCarteFormSubmit);

  const carteInput = document.getElementById("carte-plat");
  carteInput.addEventListener("input", () => {
      fillCarteDescriptionPrixIfKnown();
      updateCarteMiniBtn();
  });
}

function handleCarteFormSubmit(e) {
  e.preventDefault();

  const cartePlatInput = document.getElementById("carte-plat");
  const carteDescriptionInput = document.getElementById("carte-description");
  const cartePrixInput = document.getElementById("carte-prix");
  const carteMessage = document.querySelector(".message-carte");
  const form = e.target

  clearCarteErrors(cartePlatInput, cartePrixInput, carteMessage);

  const isValid = validateCarteForm(cartePlatInput, cartePrixInput, carteMessage);

  if(!isValid) return;

  const formData = new FormData(form);

  fetch("../php/admin_save_carte.php", {
      method: "POST",
      body: formData
  })

  .then(response => response.json())
  .then(data => handleCarteResponse(data, carteMessage, cartePlatInput, carteDescriptionInput, cartePrixInput))
  .catch((e) => {
    console.log(e);
    
    carteMessage.textContent = "❌ Une erreur est survenue."
    carteMessage.classList.add("message-error");
  });
}

function validateCarteForm(cartePlatInput, cartePrixInput, carteMessage) {
  const platCarte = cartePlatInput.value.trim();
  const prixCarte = cartePrixInput.value.trim();
  const prixRegex = /^\d{1,2}[.,]\d{2}$/;

  if (!platCarte || !prixCarte) {
    carteMessage.textContent = "❌ Merci de remplir un plat ET un prix.";
    carteMessage.classList.add("message-error");
    if (!platCarte) cartePlatInput.classList.add("input-error");
    if (!prixCarte) cartePrixInput.classList.add("input-error");
    return false;
    }

  if (!prixRegex.test(prixCarte)) {
    carteMessage.textContent = "❌ Format de prix invalide";
    carteMessage.classList.add("message-error");
    cartePrixInput.classList.add("input-error");
    return false;
  }

  return true;
}

function clearCarteErrors(cartePlatInput, cartePrixInput, carteMessage) {
  cartePlatInput.classList.remove("input-error");
  cartePrixInput.classList.remove("input-error");
  carteMessage.textContent = "";
  carteMessage.classList.remove("message-error", "message-success");
}

function handleCarteResponse(data, carteMessage, cartePlatInput, carteDescriptionInput, cartePrixInput) {
  if (data.success) {
    carteMessage.textContent = "✅ " + data.message;
    carteMessage.classList.add("message-success");
    carteDescriptionInput.value = "";
    cartePlatInput.value = "";
    cartePrixInput.value= "";
    setTimeout(() => {
        carteMessage.textContent = "";
        carteMessage.classList.remove("message-success");
      } , 4000
    );
    loadCarte();
  } else {
    carteMessage.textContent = "❌ " + data.message;
    carteMessage.classList.add("message-error");
    // setTimeout(() => {
    //     carteMessage.textContent = "";
    //     carteMessage.classList.remove("message-error");
    //   }, 4000
    // );
  }
}

let allCarte = [];

function loadCarte() {
  fetch("../php/admin_get_carte.php")
    .then(response => response.json())
    .then(data => {
      allCarte = data;
      populateCarteDatalist(data);
    });
}

function populateCarteDatalist(carte) {
  const datalist = document.getElementById("carte-list-datalist");
  if(!datalist) return;
  datalist.innerHTML = "";

  carte.forEach(elem => {
    const option = document.createElement("option");
    option.value = elem.nom;
    option.textContent = `${elem.nom} (${parseFloat(elem.prix).toFixed(2)} €)`;
    datalist.appendChild(option);
  });
}

function fillCarteDescriptionPrixIfKnown() {
  const carteInput = document.getElementById("carte-plat");
  const prixInput = document.getElementById("carte-prix");
  const descriptionInput = document.getElementById("carte-description")
  const found = allCarte.find(s => s.nom === carteInput.value);


 
  if (found) {
    prixInput.value = parseFloat(found.prix).toFixed(2);
    descriptionInput.value = found.description;
  }

}

function updateCarteMiniBtn () {
  const cartePlatInput = document.getElementById("carte-plat");
  const container = document.getElementById("carte-btn-container");
  container.innerHTML = "";

  const found = allCarte.find(s => s.nom === cartePlatInput.value);
  if(!found) return;

  const btn = document.createElement("button");
  btn.type = "button";
  btn.className = "mini-btn";
  btn.title = found.visible ? "Masquer" : "Afficher";
  btn.innerHTML = found.visible ? "🚫" : "👁";

  btn.addEventListener("click", () => {
    toggleCarteVisibility(found)
  });

  container.appendChild(btn);
}

function toggleCarteVisibility(carte) {
  fetch("/lami-fritsch-version-backend/assets/php/admin_toggle_carte_visibility.php", {
    method: "POST",
    headers:  {"Content-Type": "application/json"},
    body: JSON.stringify({
      id: carte.id,
      visible: carte.visible ? 0 : 1
    }) 
  })
    .then(response => response.json())
    .then(data => {
      if(!data.success) {
        alert(data.message || "Erreur lors de la modification.");
        return;
      }
        carte.visible = carte.visible ? 0 : 1
        updateCarteMiniBtn();
    })
    .catch(error => {
      alert("Erreur technique ou session expirée.");
      console.log(error);
    });
}