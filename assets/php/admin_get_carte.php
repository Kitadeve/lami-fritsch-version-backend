<?php
//session admin
require_once("./admin_session.php");
// Connexion à la bdd
require_once("./connexion_bdd.php");

try{

// Pour l'admin : on récupère TOUTES les suggestions
$carte = $pdo->query("SELECT id, nom, description, prix, ordre, visible FROM carte ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

$pdo = null;

} catch (PDOException $e) {
    error_log($e->getMessage());
    echo "<script>console.error(" . json_encode($e->getMessage()) . ");</script>";
    echo "<div style='color:red;'>Erreur : " . htmlspecialchars($e->getMessage()) . "</div>";
}

header('Content-Type: application/json');
echo json_encode($carte);
?>