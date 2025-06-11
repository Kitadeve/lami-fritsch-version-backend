<?php
//session admin
require_once("./admin_session.php");
// Connexion à la bdd
require_once("./connexion_bdd.php");

try {
    // Récupère tous les plats pour l'admin
    $carte = $pdo->query("SELECT id, nom, prix, ordre, visible FROM carte ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

    // Pour chaque plat, récupère ses descriptions
    foreach ($carte as &$plat) {
        $stmtDesc = $pdo->prepare("SELECT id, description FROM carte_descriptions WHERE carte_id = ?");
        $stmtDesc->execute([$plat['id']]);
        $plat['descriptions'] = $stmtDesc->fetchAll(PDO::FETCH_ASSOC);
    }
    unset($plat);

    $pdo = null;

} catch (PDOException $e) {
    error_log($e->getMessage());
    echo "<script>console.error(" . json_encode($e->getMessage()) . ");</script>";
    echo "<div style='color:red;'>Erreur : " . htmlspecialchars($e->getMessage()) . "</div>";
}

header('Content-Type: application/json');
echo json_encode($carte);
?>