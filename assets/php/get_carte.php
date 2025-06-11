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

try{
$carte = $pdo->query("SELECT id, nom, ordre prix, categorie FROM carte WHERE visible = 1 ORDER BY ordre")->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($carte);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
    error_log($e->getMessage());
    echo "<script>console.error(" . json_encode($e->getMessage()) . ");</script>";
    echo "<div style='color:red;'>Erreur : " . htmlspecialchars($e->getMessage()) . "</div>";
}

?>