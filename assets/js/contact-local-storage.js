const form = document.querySelector("form");
const validation = document.querySelector(".validation");
const popUp = document.querySelector(".validation-card");
const btn = document.querySelector(".validation-confirm");

let validationTimeout = null

// Fonction de nettoyage des entrées utilisateur pour éviter les attaques xss
function sanitizeInput(input) {
  return input.replace(/</g, "&lt;")
              .replace(/>/g, "&gt;")
              .replace(/["']/g, "")
              .replace(/&/g, "&amp;");
}

// Autorise uniquement lettres, espaces, tirets et apostrophes
function isValidName(name) {
  return /^[a-zA-ZÀ-ÿ '-]+$/.test(name);
}

function isValidPhoneNumber(phone) {
  // Français
  const fr = /^(\+33|0)[1-9](?:[\s.-]?\d{2}){4}$/;
  // Allemand
  const de = /^(\+49|0049|0)[1-9][0-9\s.-]{3,14}$/;
  return fr.test(phone) || de.test(phone);
}

function isValidEmail (email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

form.addEventListener('submit', function(e){
    e.preventDefault();

    // On récupère les valeurs brutes
    const lastNameRaw = form.lastName.value.trim();
    const firstNameRaw = form.firstName.value.trim();
    const emailRaw = form.email.value.trim();
    const phoneNumberRaw = form.phoneNumber.value.trim();
    const dateTimeRaw = form.dateTime.value.trim();
    const groupSizeRaw = form.groupSize.value.trim();
    const eventTypeRaw = form.eventType.value.trim();
    const messageRaw = form.message.value.trim();
    const subscribeNews = form.subscribeNews.checked;

    const groupSize = parseInt(groupSizeRaw, 10);

    // Réinitialisation des flags
    // let inputLastNameIsValid = false;
    // let inputFirstNameIsValid = false;
    // let inputEmailIsValid = false;
    // let inputGroupSizeIsValid = false;

    let errorMessage = "";

    // Validation des champs sur la donnée brute
    if (lastNameRaw === "" || !isValidName(lastNameRaw) || lastNameRaw.length > 50) {
        errorMessage = "Veuillez entrer un nom valide (lettres, espaces, tirets, apostrophes).";
    } 
    else if (firstNameRaw === "" || !isValidName(firstNameRaw) || firstNameRaw.length > 50) {
        errorMessage = "Veuillez entrer un prénom valide (lettres, espaces, tirets, apostrophes).";
    }
    else if (!isValidEmail(emailRaw) ||emailRaw.length > 100) {
        errorMessage = "Veuillez entrer une adresse e-mail valide.";
    }
    else if (phoneNumberRaw === "" || !isValidPhoneNumber(phoneNumberRaw)) {
        errorMessage = "Veuillez entrer un numéro de téléphone valide."
    }
    else if (isNaN(groupSize) || groupSize < 16 || groupSize > 100) {
        errorMessage = "Le nombre de convives doit être compris entre 16 et 100.";
        // console.log(groupSize); 
    }
    else if (messageRaw.length > 1000) {
      errorMessage = "Votre message est trop long."
    }
    

    // si il n'y pas de message d'erreur, il n'y a pas d'erreur et on peut enregistrer le contenu du formulaire

    if (errorMessage === "") {
        form.classList.add("active")
        validation.innerText = "Formulaire envoyé, merci !";
        popUp.classList.add("validation-succes");
        popUp.classList.remove("validation-error");
        // Ici, tu peux envoyer le formulaire via AJAX si besoin

          // On sanitize uniquement pour l'affichage ou l'envoi
        const contact = {
             lastName: sanitizeInput(lastNameRaw),
             firstName: sanitizeInput(firstNameRaw),
             email: sanitizeInput(emailRaw),
             phoneNumber: sanitizeInput(phoneNumberRaw),
             dateTime: new Date(dateTimeRaw).toLocaleString(),
             eventType: sanitizeInput(eventTypeRaw),
             message: sanitizeInput(messageRaw),
             check: subscribeNews
        };
        // console.log(contact);
        // console.log(subscribeNews);
        
        // Récupérer les messages existants ou initialiser un tableau vide
        const messages = JSON.parse(localStorage.getItem('contactMessages')) || [];

        // Ajouter le nouveau message
        messages.push(contact);

        // Enregistrer dans le localStorage
        localStorage.setItem('contactMessages', JSON.stringify(messages));
        
        // Réinitialiser le formulaire
        this.reset();
    } 
    else {
        form.classList.add("active")
        validation.innerText = errorMessage;
        popUp.classList.add("validation-error");
        popUp.classList.remove("validation-succes");
    }

  // reset de la valeur 

    if (validationTimeout) {
        clearTimeout(validationTimeout);
    }

    validationTimeout = setTimeout(function () {
        validation.innerText = "";
        popUp.classList.remove("validation-error");
        popUp.classList.remove("validation-succes");
        form.classList.remove("active");
    }, 4000);
});


// Bouton pour fermer la fenêtre de message d'information et réinitialiser le timeout

btn.addEventListener("click", function(){
    form.classList.remove("active");
    validation.innerText = "";
    popUp.classList.remove("validation-error");
    popUp.classList.remove("validation-succes");
    console.log(btn, validationTimeout)
    if (validationTimeout) {
        clearTimeout(validationTimeout);
    }
});


// Affichage de la date d'aujourd'hui dans le formulaire
// Quand la page est chargée...
document.addEventListener("DOMContentLoaded", function() {
  // On cherche le champ de type "datetime-local" dans le formulaire
  const dateTimeInput = form.querySelector('input[type="datetime-local"]');
  if (dateTimeInput) {
    // On crée un objet Date pour avoir la date et l'heure actuelles
    const now = new Date();

    // On prépare chaque partie de la date (année, mois, jour, heure, minute)
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0'); // Mois commence à janvier = 0, hors, on veut afficher 01
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');

    // On assemble la date au format attendu par le champ (ex: 2025-05-28T14:30)
    const formatted = `${year}-${month}-${day}T${hours}:${minutes}`;

    // On met cette valeur dans le champ du formulaire
    dateTimeInput.value = formatted;
  }
});

// Affichage de la date d'aujourd'hui dans le formulaire vesrion compacte

// document.addEventListener("DOMContentLoaded", function() {
//   const dateTimeInput = form.querySelector('input[type="datetime-local"]');
//   if (dateTimeInput) {
//     const now = new Date();
//     // Format : YYYY-MM-DDTHH:MM (sans les secondes)
//     const pad = n => n.toString().padStart(2, '0');
//     const formatted = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`;
//     dateTimeInput.value = formatted;
//   }
// });