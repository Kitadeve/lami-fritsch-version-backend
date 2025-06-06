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
header('Location: admin_gestion.php');
exit();