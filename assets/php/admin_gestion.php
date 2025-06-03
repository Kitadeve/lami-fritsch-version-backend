<?php
// filepath: c:\xampp\htdocs\lami-fritsch-live\assets\php\admin_gestion.php
session_start();
if (empty($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}
$pdo = new PDO("mysql:host=127.0.0.1;port=3307;dbname=restaurant;charset=utf8", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$entrees = $pdo->query("SELECT id, nom FROM entrees ORDER BY nom")->fetchAll();
$plats = $pdo->query("SELECT id, nom FROM plats ORDER BY nom")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des entrées et plats</title>
    <link rel="stylesheet" href="../css/styleguide.css">
    <link rel="stylesheet" href="../css/globals.css">
    <!-- <link rel="stylesheet" href="../css/admin.css"> -->
    <link rel="stylesheet" href="../css/admin-php.css">
</head>
<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
  // Confirmation pour la suppression
  document.querySelectorAll('button[name="action"][value="supprimer"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
      if (!confirm('Confirmer la suppression ? Cette action est irréversible.')) {
        e.preventDefault();
      }
    });
  });

  // Confirmation pour la modification
  document.querySelectorAll('button[name="action"][value="modifier"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
      if (!confirm('Confirmer la modification du nom ?')) {
        e.preventDefault();
      }
    });
  });
});

document.querySelectorAll('.auto-resize').forEach(function(textarea) {
  function resize() {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
  }
  textarea.addEventListener('input', resize);
  // Redimensionne au chargement
  resize();
});
</script> -->

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Confirmation pour la suppression
  document.querySelectorAll('button[name="action"][value="supprimer"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
      if (!confirm('Confirmer la suppression ? Cette action est irréversible.')) {
        e.preventDefault();
      }
    });
  });

  // Confirmation pour la modification
  document.querySelectorAll('button[name="action"][value="modifier"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
      if (!confirm('Confirmer la modification du nom ?')) {
        e.preventDefault();
      }
    });
  });

  // Auto-resize des textareas
  document.querySelectorAll('.auto-resize').forEach(function(textarea) {
    function resize() {
      textarea.style.height = 'auto';
      textarea.style.height = textarea.scrollHeight + 'px';
    }
    textarea.addEventListener('input', resize);
    resize(); // Redimensionne au chargement
  });
});
</script>

<body>
  <main>
    <h2>Gestion de la bibliothèque</h2>
    <section class="gestion-table">
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
            <td>
              <?php if ($entree): ?>
                <form class="gestion-form" method="POST" action="admin_gestion_entree.php">
                  <input class="gestion-input" type="text" name="nouveau_nom" value="<?= htmlspecialchars($entree['nom']) ?>">
                  <input type="hidden" name="id" value="<?= $entree['id'] ?>">
                  <button class="mini-btn" type="submit" name="action" value="modifier" title="Modifier">&#9998;</button>
                  <button class="mini-btn delete" type="submit" name="action" value="supprimer" title="Supprimer" onclick="return confirm('Supprimer cette entrée ?')">&#10006;</button>
                </form>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($plat): ?>
                <form class="gestion-form" method="POST" action="admin_gestion_plat.php">
                  <textarea class="gestion-input auto-resize" name="nouveau_nom" rows="1"><?= htmlspecialchars($plat['nom']) ?></textarea>
                  <input type="hidden" name="id" value="<?= $plat['id'] ?>">
                  <button class="mini-btn" type="submit" name="action" value="modifier" title="Modifier">&#9998;</button>
                  <button class="mini-btn delete" type="submit" name="action" value="supprimer" title="Supprimer" onclick="return confirm('Supprimer ce plat ?')">&#10006;</button>
                </form>
              <?php endif; ?>
            </td>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>

      <a class="cta" href="./admin.php">Retour</a>
    </section>

  </main>
</body>
</html>