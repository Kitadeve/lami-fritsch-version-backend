document.addEventListener("DOMContentLoaded", () => {

});

function initCarteForm() {
  const form = document.getElementById("carte-form");
  form.addEventListener("submit", handleFormSubmit);

  const carteInput = document.getElementById("platCarte");
  carteInput.addEventListener("input", () => {
      fillPrixIfKnown();
      updateMiniBtn();
  });
}

function handleFormSubmit(e) {
  e.preventDefault();

  const carteInput = document.getElementById("platCarte");
  const prixInput = document.getElementById("prixCarte");
  const message = document.querySelector(".message-carte");
  const form = e.target

  clearErrors(carteInput, prixInput, message);

  const isValid = validateForm(carteInput, prixInput, message);

  if(!isValid) return;

  const formData = new FormData(form);

  fetch("../php/admib_save_carte.php", {
      method: "POST",
      body: formData
  })

  .then(response => response.json())
  .then(data => handleResponse(data, message, carteInput, prixInput))
  .catch(() => {
    message.textContent = "❌ Une erreur est survenue.";
    message.classList.add("message-error");
  });
}

function validateForm(carteInput, prixInput, message) {
  const platCarte = platCarte.value.trim();
  const prixCarte = prixInput.value.trim();
  const prixRegex = /^\d{1,2}[.,]\d{2}$/;
}