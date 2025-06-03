<?php
session_start();
if (empty($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Connexion
$pdo = new PDO("mysql:host=127.0.0.1;port=3307;dbname=restaurant;charset=utf8", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Récupérer les entrées et plats existants
$entrees = $pdo->query("SELECT id, nom FROM entrees ORDER BY nom")->fetchAll();
$plats = $pdo->query("SELECT id, nom FROM plats ORDER BY nom")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Admin - Menu du jour</title>
  <link rel="stylesheet" href="../css/styleguide.css">
  <link rel="stylesheet" href="../css/globals.css">
  <link rel="stylesheet" href="../css/admin-php.css">
</head>
<body>
  <main>
    <h1>Définir un menu du jour</h1>

    <?php if (isset($_GET['success'])): ?>
      <p style="color:green;">✅ Menu enregistré avec succès !</p>
    <?php elseif (isset($_GET['error'])): ?>
      <p style="color:red;">❌ Erreur lors de l'enregistrement.</p>
    <?php endif; ?>

    <form method="POST" action="admin_save.php">
      <?php foreach (['Mercredi','Jeudi','Vendredi'] as $jour): ?>
        <fieldset>
          <legend><?= $jour ?></legend>
          <label>Entrée :</label>
          <input type="text" name="entree_nouvelle[<?= $jour ?>]" list="entrees-list">
          <label>Plat :</label>
          <input type="text" name="plat_nouveau[<?= $jour ?>]" list="plats-list">
        </fieldset>
      <?php endforeach; ?>

      <datalist id="entrees-list">
        <?php foreach ($entrees as $entree): ?>
          <option value="<?= htmlspecialchars($entree['nom']) ?>">
        <?php endforeach; ?>
      </datalist>
      <datalist id="plats-list">
        <?php foreach ($plats as $plat): ?>
          <option value="<?= htmlspecialchars($plat['nom']) ?>">
        <?php endforeach; ?>
      </datalist>

      <button class="cta send" type="submit">Enregistrer</button>
      <a class="cta gestion" href="admin_gestion.php">Gestion</a>
      <a class="cta logout" href="logout.php">Déconnexion</a>
    </form>

    
  </main>
  
</body>
</html>
