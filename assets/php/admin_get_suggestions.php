<?php
session_start();
if (empty($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Connexion à la bdd
require_once("./connexion_bdd.php");

// Pour l'admin : on récupère TOUTES les suggestions
$suggestions = $pdo->query("SELECT id, nom, prix, visible FROM suggestions ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($suggestions);
?>