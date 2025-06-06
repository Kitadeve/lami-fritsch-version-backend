<?php 
session_start();
header('Content-Type: application/json');

if (empty($_SESSION["admin"])) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "Non autorisé"]);
    exit();
}

try{

    // Connexion à la bdd
require_once("./connexion_bdd.php");

if (
    !isset($_POST["suggestion"], $_POST["prix"]) ||
    trim($_POST["suggestion"] === "") ||
    trim($_POST["prix"]) === ""
) {
    http_response_code(400);
    exit("Champs manquants");
}

$nom = trim($_POST["suggestion"]);
$prix = str_replace(",",".", trim($_POST["prix"]));

if (!preg_match('/^\d{1,2}\.\d{2}$/', $prix)) {
    http_response_code(400);
    echo json_encode(["succes" => false, "message" => "Format de prix invalide"]);
    exit();
}

$stmt = $pdo->prepare("INSERT INTO suggestions (nom, prix) VALUES (?, ?)");
$stmt->execute([$nom, $prix]);

echo json_encode(["success"=> true, "message" => "Sugestion enregistrée avec succés !"]);

$pdo = null;

} catch (PDOException $e) {
    http_response_code(500);
    if ($e->getCode() == 23000) { // Doublon
        echo json_encode(["success" => false, "message" => "Cette suggestion existe déjà, utilisez le bouton pour l'afficher"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur serveur : " . $e->getMessage()]);
    }
}
?>