<?php
require_once './assets/php/connexion_bdd.php';

// Fetch all plats
$stmt = $pdo->query("SELECT * FROM carte WHERE visible = 1 ORDER BY categorie, ordre, nom");
$carte = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all descriptions
$stmt = $pdo->query("SELECT carte_id, description FROM carte_descriptions ORDER BY id");
$descs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group descriptions by carte_id
$descriptions_by_carte = [];
foreach ($descs as $desc) {
    $descriptions_by_carte[$desc['carte_id']][] = $desc['description'];
}
?>


<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Carte et menus - L'ami Fritsch</title>
    <meta name="description" content="Découvrez la carte, les plats du jour et les suggestions du restaurant L'ami Fritsch à Ettendorf. Spécialités alsaciennes, tartes flambées, fait maison">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow">
    <link rel="icon" type="image/x-icon" href="./assets/icons/Favicon.svg">
    <link rel="stylesheet" href="./assets/css/styleguide.css">
    <link rel="stylesheet" href="./assets/css/globals.css">
    <link rel="stylesheet" href="./assets/css/carte-et-menus.css">
  </head>

  <body>

    <!-------------------- header ------------------------------>
  
    <main>
      
      <h1>Carte et menus</h1>

      <!------------------------ plats du jour --------------------------->
      <section class="plats-du-jour">
        <div class="bord"></div>
        <div class="centre">
          <div class="head">
            <h2 >Les plats du jour</h2>
            <div class="menu-plat-seul">
              <span >Menu - 11,50 €</span>
              <span >Plat seul - 9 €</span>
            </div>
          </div>
          <div class="cards-pdj">
            <div class="card-jour jour-un">
              <h3 class="jour">Mercredi</h3>
              <div class="frame">
                <div class="entree">Salade nordique</div>
                <div class="trait"></div>
                <p class="plat">Fleischnaka maison, sauce madère, salade verte</p>
              </div>
            </div>
            <div class="card-jour jour-deux">
              <h3 class="jour">Jeudi</h3>
              <div class="frame">
                <p class="entree">Salade de tomates au thon<br />et vinaigre balsamique</p>
                <div class="trait"></div>
                <p class="plat">Assiette mix grill lard, merguez, échine de porc<br />Sauce barbecue, maïs et potato</p>
              </div>
            </div>
            <div class="card-jour jour-trois">
              <h3 class="jour">Vendredi</h3>
              <div class="frame">
                <div class="entree">Quiche lorraine</div>
                <div class="trait"></div>
                <p class="plat">Filet de julienne panné<br />Pomme de terre vapeur, sauce holandaise</p>
              </div>
            </div>
          </div>
        </div>
        <div class="bord"></div>
      </section> 

      <!------------------- suggestions ----------------------->
      <section class="carte-suggestions suggestions">
        <div class="bord"></div>
          <div class="centre">
            <h2 >Les suggestions</h2>
            <div class="cards-plats-suggestions">

              <div class="card-plats">
                <p class="plat">Véritable rosbeef à <strong class="plat">l'alsacienne</strong></p>
                <div class="trait-prix">
                  <div class="trait-plats"></div>
                  <p>18,50 €</p>
                </div>
              </div>

              <div class="card-plats">
                <p class="plat">Camembert frit, pommes de terre sautées, salade</p>
                <div class="trait-prix">
                  <div class="trait-plats"></div>
                  <p>16,50 €</p>
                </div>
              </div>

              <div class="card-plats">
                <p class="plat">Hampe de bœuf à l'échalotte, frites <strong class="plat">"fait-maison"</strong></p>
                <div class="trait-prix">
                  <div class="trait-plats"></div>
                  <p class="prix">18,50 €</p>
                </div> 
              </div>

            </div>
          </div>
        <div class="bord"></div>
      </section>

      <!-- carte -->
      <section class="carte-suggestions carte">
        <div class="bord"></div>
          <div class="centre">
            <h2 >La carte</h2>
            
            <!-- titres pour filtres desktop -->
            <div class="filtre">
              <h3>
                <button class="filtre-plats">Plats</button>
              </h3>
              <h3>
                <button class="filtre-tartes-flambees">Tartes flambées</button>
              </h3>
              <h3>
                <button class="filtre-desserts">Desserts  
              </h3>
            </div>

            <!------------ Plats ---------->
            <h3 class="mobile" >Plats</h3>

            <div class="plats-filtre">
              <div class="cards-plats-suggestions">
                <?php foreach ($carte as $plat): ?>
                  <?php if ($plat['categorie'] === 'plats'): ?>
                    <div class="card-plats">
                      <p class="plat"><?= htmlspecialchars($plat['nom']) ?></p>
                      <?php if (!empty($descriptions_by_carte[$plat['id']])): ?>
                        <ul class="description">
                          <?php foreach ($descriptions_by_carte[$plat['id']] as $desc): ?>
                            <li><?= htmlspecialchars($desc) ?></li>
                          <?php endforeach; ?>
                        </ul>
                      <?php endif; ?>
                      <div class="trait-prix">
                        <div class="trait-plats"></div>
                        <p><?= number_format($plat['prix'], 2, ',', ' ') ?> €</p>
                      </div>
                    </div>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            </div>

            <!-------------- tartes-flambees ------------>

            <h3 class="mobile">Tartes flambées</h3>

            <div class="tartes-flambees">
              <div class="cards-plats-suggestions">
                <?php foreach ($carte as $plat): ?>
                  <?php if ($plat['categorie'] === 'tartes_flambees'): ?>
                    <div class="card-plats">
                      <p class="plat"><?= htmlspecialchars($plat['nom']) ?></p>
                      <?php if (!empty($descriptions_by_carte[$plat['id']])): ?>
                        <ul class="description">
                          <?php foreach ($descriptions_by_carte[$plat['id']] as $desc): ?>
                            <li><?= htmlspecialchars($desc) ?></li>
                          <?php endforeach; ?>
                        </ul>
                      <?php endif; ?>
                      <div class="trait-prix">
                        <div class="trait-plats"></div>
                        <p><?= number_format($plat['prix'], 2, ',', ' ') ?> €</p>
                      </div>
                    </div>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            </div>

            
            
            <!----------------- Desserts ----------------->
            <h3 class="mobile">Desserts</h3>

                        <div class="desserts">
              <div class="cards-plats-suggestions">
                <?php foreach ($carte as $plat): ?>
                  <?php if ($plat['categorie'] === 'desserts'): ?>
                    <div class="card-plats">
                      <p class="plat"><?= htmlspecialchars($plat['nom']) ?></p>
                      <?php if (!empty($descriptions_by_carte[$plat['id']])): ?>
                        <ul class="description">
                          <?php foreach ($descriptions_by_carte[$plat['id']] as $desc): ?>
                            <li><?= htmlspecialchars($desc) ?></li>
                          <?php endforeach; ?>
                        </ul>
                      <?php endif; ?>
                      <div class="trait-prix">
                        <div class="trait-plats"></div>
                        <p><?= number_format($plat['prix'], 2, ',', ' ') ?> €</p>
                      </div>
                    </div>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            </div>

      <a class="cta" href="reservations.html">Réservations</a>
      
    </main>

   <!-- footer -->
    <script src="./assets/js/global.js"></script>
    <script src="./assets/js/partials.js"></script>
    <script src="./assets/js/pdj.js"></script>
    <script src="./assets/js/pdj-php.js"></script>
    <script src="./assets/js/suggestions-php.js"></script>
    <!-- <script src="./assets/js/carte-php.js"></script> -->
    <script src="./assets/js/intersectionobserver.js"></script>
    <script src="./assets/js/filtres.js"></script>
  </body>
</html>


