const reload = document.querySelector("#reload");

function getCat() {
  const chats = document.querySelector(".chats");

  fetch("../php/cat-proxy.php")
    .then(response => response.json())
    .then(result => {
      chats.innerHTML = "";
      const catData = result[0];
      const img = document.createElement("img");
      
      img.src = catData.url;
      img.alt = "Photo de chat aléatoire";

      const breedInfo = catData.breeds[0];
      const breedName = document.createElement("h3");
      breedName.textContent = `Race : ${breedInfo.name}`;

      const temperament = document.createElement("p");
      temperament.textContent = `Tempérament : ${breedInfo.temperament}`;

      chats.appendChild(img);
      chats.appendChild(breedName);
      chats.appendChild(temperament);
    })
    .catch(error => console.log('error', error));
}

window.addEventListener("DOMContentLoaded", getCat)
reload.addEventListener("click",getCat)