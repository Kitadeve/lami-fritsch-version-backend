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

  <div class="utilities">
    <a class="cta gestion" href="admin_gestion.php">Gestion</a>
    <a class="cta logout" href="logout.php">Déconnexion</a>
  </div> 
  <main>
    <h1>Admin</h1>

      <section class="plats-du-jour">
        <h2>Plats du jour</h2>
        <?php if (isset($_GET['success'])): ?>
          <p class="message-success" style="align-self: center;
                    text-align: center;
                    border: 4px solid green;
                    margin-bottom: 24px;
                    padding: 8px 16px;
                    border-radius: 10px;
                    background: rgba(0, 161, 27, 0.2);">✅ Menu enregistré avec succès !</p>
        <?php endif; ?> 

        <p class="message"></p>

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

          <input class="cta send" type="submit" value="Enregistrer">
        </form>
      </section>

      <!-- Formulaire Suggestions -->
      <section class="suggestions-admin">
        <h2>Suggestions de la semaine</h2>
        <p class="message-suggestions"></p>
        <form method="POST" action="admin_save_suggestions.php" id="suggestions-form">
          <div id="suggestions-list">
           <label for="suggestion">Plat :</label>
           <input type="text" name="suggestion" id="suggestion">

           <label for="prix">Prix :</label>
           <input type="text" name="prix" id="prix">
          </div>
          <button type="submit" class="cta send">Enregistrer les suggestions</button>
        </form>
      </section>



    <div class="cats">
      <div class="chats"></div>
      <button class="cta" id="reload">Reload</button>
    </div>

    
    
  </main>
    <script src="../js/cats.js"></script>
    <script src="../js/global.js"></script>
    <script src="../js/partials.js"></script>
    <script src="../js/admin.js"></script>
    <script src="../js/admin-suggestions.js"></script>
</body>
</html>
