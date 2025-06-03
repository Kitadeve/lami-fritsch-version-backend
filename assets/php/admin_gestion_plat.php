<?php
session_start();
if (empty($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}
$pdo = new PDO("mysql:host=127.0.0.1;port=3307;dbname=restaurant;charset=utf8", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
header('Location: admin.php');
exit();