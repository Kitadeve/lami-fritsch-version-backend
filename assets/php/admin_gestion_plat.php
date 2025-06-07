<?php
//session admin
require_once("./admin_session.php");;

// Connexion à la bdd
require_once("./connexion_bdd.php");

try {
$id = $_POST['id'] ?? null;
$action = $_POST['action'] ?? '';
if ($action === 'modifier' && $id && isset($_POST['nouveau_nom'])) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM plats WHERE nom = :nom AND id != :id");
    $stmt->execute([':nom' => $_POST['nouveau_nom'], ':id' => $id]);
    if ($stmt->fetchColumn() == 0) {
        $stmt = $pdo->prepare("UPDATE plats SET nom = :nom WHERE id = :id");
        $stmt->execute([':nom' => $_POST['nouveau_nom'], ':id' => $id]);
    }
} elseif ($action === 'supprimer' && $id) {
    // Supprimer d'abord les menus du jour qui utilisent ce plat
    $stmt = $pdo->prepare("DELETE FROM menus_du_jour WHERE plat_id = :id");
    $stmt->execute([':id' => $id]);
    // Puis supprimer le plat
    $stmt = $pdo->prepare("DELETE FROM plats WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

$pdo = null;

} catch (PDOException $e) {
    error_log($e->getMessage());
    echo "<script>console.error(" . json_encode($e->getMessage()) . ");</script>";
    echo "<div style='color:red;'>Erreur : " . htmlspecialchars($e->getMessage()) . "</div>";
}
header('Location: admin_gestion.php');
exit();