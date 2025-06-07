<?php
//session admin
require_once("./admin_session.php");

// Connexion à la bdd
require_once("./connexion_bdd.php");

try {
    $id = $_POST['id'] ?? null;
    $action = $_POST['action'] ?? '';

    if ($action === 'modifier' && $id && isset($_POST['nouveau_nom'], $_POST['nouveau_prix'])) {
        $nom = trim($_POST['nouveau_nom']);
        $prix = str_replace(',', '.', trim($_POST['nouveau_prix']));
        if ($nom !== '' && preg_match('/^\d{1,2}\.\d{2}$/', $prix)) {
            $stmt = $pdo->prepare("UPDATE suggestions SET nom = :nom, prix = :prix WHERE id = :id");
            $stmt->execute([':nom' => $nom, ':prix' => $prix, ':id' => $id]);
        }
    } elseif ($action === 'supprimer' && $id) {
        $stmt = $pdo->prepare("DELETE FROM suggestions WHERE id = :id");
        $stmt->execute([':id' => $id]);
    } elseif ($action === 'masquer' && $id) {
        $stmt = $pdo->prepare("UPDATE suggestions SET visible = 0 WHERE id = :id");
        $stmt->execute([':id' => $id]);
    } elseif ($action === 'afficher' && $id) {
        $stmt = $pdo->prepare("UPDATE suggestions SET visible = 1 WHERE id = :id");
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

?>