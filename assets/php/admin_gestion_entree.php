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
    if ($action === 'modifier' && $id && isset($_POST['nouveau_nom'])) {
        $stmt = $pdo->prepare("UPDATE entrees SET nom = :nom WHERE id = :id");
        $stmt->execute([':nom' => $_POST['nouveau_nom'], ':id' => $id]);
    } elseif ($action === 'supprimer' && $id) {
        // Supprimer d'abord les menus du jour qui utilisent cette entrée
        $stmt = $pdo->prepare("DELETE FROM menus_du_jour WHERE entree_id = :id");
        $stmt->execute([':id' => $id]);
        // Puis supprimer l'entrée
        $stmt = $pdo->prepare("DELETE FROM entrees WHERE id = :id");
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