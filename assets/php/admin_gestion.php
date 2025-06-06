<?php
// filepath: c:\xampp\htdocs\lami-fritsch-live\assets\php\admin_gestion.php
session_start();
if (empty($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Connexion à la bdd
require_once("./connexion_bdd.php");

$entrees = $pdo->query("SELECT id, nom FROM entrees ORDER BY nom")->fetchAll();
$plats = $pdo->query("SELECT id, nom FROM plats ORDER BY nom")->fetchAll();

// Récupération des suggestions
$suggestions = $pdo->query("SELECT id, nom, prix, visible FROM suggestions ORDER BY nom")->fetchAll();

//Fermeture de la connexion
$pdo = null;

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
                  <input class="gestion-input" type="text" name="nouveau_nom" value="<?= htmlspecialchars($entree['nom']) ?>">
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
            <tr>
              <form class="gestion-form" method="POST" action="admin_gestion_suggestion.php">
                <td data-label="Suggestion">
                  <input class="gestion-input" type="text" name="nouveau_nom" value="<?= htmlspecialchars($sugg['nom']) ?>">
                  <input type="hidden" name="id" value="<?= $sugg['id'] ?>">
                </td>
                <td data-label="Prix">
                  <input class="gestion-input" type="text" name="nouveau_prix" value="<?= htmlspecialchars(number_format($sugg['prix'], 2, '.', '')) ?>">
                </td>
                <td>
                  <button class="mini-btn" type="submit" name="action" value="modifier" title="Modifier">&#9998;</button>
                  <button class="mini-btn delete" type="submit" name="action" value="supprimer" title="Supprimer">&#10006;</button>
                  <?php if ($sugg['visible']): ?>
                    <button class="mini-btn hide" type="submit" name="action" value="masquer" title="Masquer">&#128065;</button>
                  <?php endif; ?>
                </td>
              </form>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

  <script src="../js/global.js"></script>
  <script src="../js/partials.js"></script>
  <script src="../js/admin-gestion.js"></script>

  </main>
</body>
</html>