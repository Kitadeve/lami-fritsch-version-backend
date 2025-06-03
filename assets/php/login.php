<?php
session_start();
$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? '';
    $pass = $_POST['pass'] ?? '';
    // À personnaliser : login/mot de passe
    if ($user === 'admin' && $pass === '1357') {
        $_SESSION['admin'] = true;
        header('Location: admin.php');
        exit();
    } else {
        $erreur = "Identifiants incorrects";
    }
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
</head>
<body>
  <h1>Connexion admin</h1>
  <?php if ($erreur): ?><p style="color:red;"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
  <form method="post">
    <input type="text" name="user" placeholder="Utilisateur" required><br>
    <input type="password" name="pass" placeholder="Mot de passe" required><br>
    <button class="cta" type="submit">Se connecter</button>
  </form>
</body>
</html>