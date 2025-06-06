<?php
session_start();
if (empty($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

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
    }
} catch (PDOException $e) {
    // Tu peux logger l'erreur si besoin
    // error_log($e->getMessage());
}
header('Location: admin_gestion.php');
exit();

?>