<?php

// filepath: c:\xampp\htdocs\lami-fritsch-live\assets\php\admin_gestion.php
//session admin
require_once("./admin_session.php");

// Connexion à la bdd
require_once("./connexion_bdd.php");

try{

//Récupération des entrées en plats
$entrees = $pdo->query("SELECT id, nom FROM entrees ORDER BY nom")->fetchAll();
$plats = $pdo->query("SELECT id, nom FROM plats ORDER BY nom")->fetchAll();

// Récupération des suggestions
$suggestions = $pdo->query("SELECT id, nom, prix, visible FROM suggestions ORDER BY nom")->fetchAll();

// Récupération des plats
$carte = $pdo->query("SELECT id, nom,  prix, categorie, description, ordre, visible FROM carte ORDER BY nom")->fetchAll();

//Fermeture de la connexion
$pdo = null;

} catch (PDOException $e) {
    error_log($e->getMessage());
    echo "<script>console.error(" . json_encode($e->getMessage()) . ");</script>";
    echo "<div style='color:red;'>Erreur : " . htmlspecialchars($e->getMessage()) . "</div>";
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des entrées et plats</title>
    <link rel="stylesheet" href="../css/styleguide.css">
    <link rel="stylesheet" href="../css/globals.css">
    <link rel="stylesheet" href="../css/admin-php.css">
</head>

<body>
  <?php // require_once("../partials/header.php"); ?>

  <div class="utilities">         
      <a class="cta" href="./admin.php">Retour</a>
  </div>
  <main>
    
    <h1>Gestion de la bibliothèque</h1>
    <section class="gestion-table">
      <h2>Plats du jour</h2>
      <table>
        <thead>
          <tr>
            <th>Entrées</th>
            <th>Plats</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $max = max(count($entrees), count($plats));
          for ($i = 0; $i < $max; $i++):
            $entree = $entrees[$i] ?? null;
            $plat = $plats[$i] ?? null;
          ?>
          <tr>
            <td data-label="Entrée">
              <?php if ($entree): ?>
                <form class="gestion-form" method="POST" action="admin_gestion_entree.php">
                  <textarea class="gestion-input" type="text" name="nouveau_nom" value=""><?= htmlspecialchars($entree['nom']) ?></textarea>
                  <input type="hidden" name="id" value="<?= $entree['id'] ?>">
                  <button class="mini-btn" type="submit" name="action" value="modifier" title="Modifier">&#9998;</button>
                  <button class="mini-btn delete" type="submit" name="action" value="supprimer" title="Supprimer">&#10006;</button>
                  <!-- <button class="mini-btn hide" type="submit" name="action" value="masquer" title="Masquer">&#128065;</button> -->
                </form>
              <?php endif; ?>
            </td>
            <td data-label="Plats">
              <?php if ($plat): ?>
                <form class="gestion-form" method="POST" action="admin_gestion_plat.php">
                  <textarea class="gestion-input auto-resize" name="nouveau_nom" rows="1"><?= htmlspecialchars($plat['nom']) ?></textarea>
                  <input type="hidden" name="id" value="<?= $plat['id'] ?>">
                  <button class="mini-btn" type="submit" name="action" value="modifier" title="Modifier">&#9998;</button>
                  <button class="mini-btn delete" type="submit" name="action" value="supprimer" title="Supprimer">&#10006;</button>
                  <!-- <button class="mini-btn hide" type="submit" name="action" value="masquer" title="Masquer">&#128065;</button> -->
                </form>
              <?php endif; ?>
            </td>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>
    
    </section>

    <section class="gestion-table">

      <div class="confirm-display">
        <p class="texte-confirm"></p>
        <div class="btn">
          <button class="cta" name="action" value="oui" type="button">Oui</button>
          <button class="cta" name="action" value="non" type="button">Non</button>
        </div>
      </div>

      <h2>Suggestions</h2>
      <table>
        <thead>
          <tr>
            <th>Suggestion</th>
            <th>Prix (€)</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($suggestions as $sugg): ?>
            <tr class="suggestion-row">
              <td data-label="Suggestion">
                <textarea class="gestion-input" type="text" name="nouveau_nom" value="" form="form-<?= $sugg['id'] ?>"><?= htmlspecialchars($sugg['nom']) ?></textarea>
              </td>
              <td data-label="Prix">
                <input class="gestion-input" type="text" name="nouveau_prix" value="<?= htmlspecialchars(number_format($sugg['prix'], 2, '.', '')) ?>" form="form-<?= $sugg['id'] ?>">
              </td>
              <td class="boutons">
                <form class="gestion-form" id="form-<?= $sugg['id'] ?>" method="POST" action="admin_gestion_suggestions.php">
                  <input type="hidden" name="id" value="<?= $sugg['id'] ?>">

                  <button class="mini-btn" type="submit" name="action" value="modifier" title="Modifier">&#9998;</button>

                  <?php if ($sugg['visible']): ?>
                    <button class="mini-btn hide" type="submit" name="action" value="masquer" title="Masquer">&#128683;</button>
                  <?php else: ?>
                    <button class="mini-btn show" type="submit" name="action" value="afficher" title="Afficher">&#128065;</button>
                  <?php endif; ?>

                  <button class="mini-btn delete" type="submit" name="action" value="supprimer" title="Supprimer">&#10006;</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

<section class="gestion-table carte">
  <h2>Carte</h2>
  <table>
    <thead>
      <tr>
        <th>Plat</th>
        <th>Description</th>
        <th>Prix (€)</th>
        <th>Catégorie</th>
        <th>Ordre</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($carte as $plat): ?>
        <tr class="carte-row">
          <td data-label="Plat">
            <textarea class="gestion-input" type="text" name="nouveau_nom" value="" form="form-carte-<?= $plat['id'] ?>"><?= htmlspecialchars($plat['nom']) ?></textarea>
          </td>
          <td data-label="Description">
            <textarea class="gestion-input" type="text" name="nouvelle_description" value="" form="form-carte-<?= $plat['id'] ?>"><?= htmlspecialchars($plat['description']) ?></textarea>
          </td>
          <td data-label="Prix">
            <input class="gestion-input" type="text" name="nouveau_prix" value="<?= htmlspecialchars(number_format($plat['prix'], 2, '.', '')) ?>" form="form-carte-<?= $plat['id'] ?>">
          </td>
          <td data-label="Catégorie">
            <select class="gestion-input" name="nouvelle_categorie" form="form-carte-<?= $plat['id'] ?>">
              <option value="plats" <?= $plat['categorie'] === 'plats' ? 'selected' : '' ?>>Plat</option>
              <option value="tartes_flambees" <?= $plat['categorie'] === 'tartes_flambees' ? 'selected' : '' ?>>Tarte flambée</option>
              <option value="desserts" <?= $plat['categorie'] === 'desserts' ? 'selected' : '' ?>>Dessert</option>
            </select>
          </td>
          <td data-label="Ordre">
            <input class="gestion-input" type="number" name="nouvel_ordre" value="<?= (int)$plat['ordre'] ?>" form="form-carte-<?= $plat['id'] ?>" style="width:60px;">
          </td>
          <td class="boutons">
            <form class="gestion-form" id="form-carte-<?= $plat['id'] ?>" method="POST" action="admin_gestion_carte.php">
              <input type="hidden" name="id" value="<?= $plat['id'] ?>">

              <button class="mini-btn" type="submit" name="action" value="modifier" title="Modifier">&#9998;</button>

              <?php if ($plat['visible']): ?>
                <button class="mini-btn hide" type="submit" name="action" value="masquer" title="Masquer">&#128683;</button>
              <?php else: ?>
                <button class="mini-btn show" type="submit" name="action" value="afficher" title="Afficher">&#128065;</button>
              <?php endif; ?>

              <button class="mini-btn delete" type="submit" name="action" value="supprimer" title="Supprimer">&#10006;</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>

  <script src="../js/global.js"></script>
  <script src="../js/partials.js"></script>
  <script src="../js/admin-gestion.js"></script>
  </main>
  <?php //require_once("../partials/footer.php"); ?> 

</body>
</html>