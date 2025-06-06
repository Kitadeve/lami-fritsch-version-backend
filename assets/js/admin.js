document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");
  const jours = ["Mercredi", "Jeudi", "Vendredi"];
  const message = document.querySelector(".message");

  form.addEventListener("submit", function (e) {
    let atLeastOneCouple = false;

    jours.forEach(jour => {
      const entree = form.querySelector(`input[name="entree_nouvelle[${jour}]"]`);
      const plat = form.querySelector(`input[name="plat_nouveau[${jour}]"]`);
      // Vérifie si les deux champs sont remplis pour ce jour
      if (entree.value.trim() && plat.value.trim()) {
        atLeastOneCouple = true;
        entree.classList.remove("input-error");
        plat.classList.remove("input-error");
        entree.classList.add("input-success");
        plat.classList.add("input-success");
      } else {
        entree.classList.remove("input-success");
        plat.classList.remove("input-success");
      }
    });

    if (!atLeastOneCouple) {
      e.preventDefault();
      // Mets en rouge tous les champs pour signaler l'erreur
      jours.forEach(jour => {
        const entree = form.querySelector(`input[name="entree_nouvelle[${jour}]"]`);
        const plat = form.querySelector(`input[name="plat_nouveau[${jour}]"]`);
        entree.classList.add("input-error");
        plat.classList.add("input-error");
      });
      message.innerHTML = "❌ Merci de remplir au moins un couple entrée + plat pour un jour.";
      message.classList.add("message-error");
    }
  });

  const params = new URLSearchParams(window.location.search);
  if (params.has('success')) {
    setTimeout(() => {
      // Masque le message
      const successMsg = document.querySelector(".message-success");
      if (successMsg) successMsg.style.display = "none";
      // Enlève le paramètre de l'URL
      params.delete('success');
      const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
      window.history.replaceState({}, '', newUrl);
    }, 2000);
  }

  // Optionnel : feedback visuel en temps réel
  form.querySelectorAll('input[type="text"]').forEach(input => {
    input.addEventListener("input", function () {
      if (input.value.trim()) {
        input.classList.remove("input-error");
        input.classList.add("input-success");
      } else {
        input.classList.remove("input-success");
      }
    });
  });

});