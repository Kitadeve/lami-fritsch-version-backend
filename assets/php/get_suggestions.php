<?php
session_start();
if (empty($_SESSION['admin'])) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Non autorisé']);
    exit();
}

// Connexion à la bdd
require_once("./connexion_bdd.php");

$suggestions = $pdo->query("SELECT id, nom, prix FROM suggestions WHERE visible = 1 ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($suggestions);
?>