<?php
session_start();
$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? '';
    $pass = $_POST['pass'] ?? '';

    // Connexion à la bdd
    require_once("./connexion_bdd.php");
    
    $stmt->execute(['user' => $user]);
    $row = $stmt->fetch();
    if ($row && password_verify($pass, $row['password'])) {
        $_SESSION['admin'] = true;
        header('Location: admin.php');
        exit();
    } else {
        $erreur = "Identifiants incorrects";
      }
    $pdo = null;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Connexion admin</title>
  <link rel="stylesheet" href="../css/styleguide.css">
  <link rel="stylesheet" href="../css/globals.css">
  <link rel="stylesheet" href="../css/contact.css">
  <link rel="stylesheet" href="../css/admin.css">
  <link rel="stylesheet" href="../css/admin-php.css">
</head>
<body>
  <main class="login-page">
  <h1>Connexion admin</h1>
  <?php if ($erreur): ?><p class="login-error"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
  <form method="post">
    <input type="text" name="user" placeholder="Utilisateur" required><br>
    <input type="password" name="pass" placeholder="Mot de passe" required><br>
    <input class="cta" type="submit" value="Se connecter">
  </form>
  </main>
  <script src="../js/partials.js"></script>
      <script src="../js/global.js"></script>
    <script src="../js/partials.js"></script>
</body>
</html>