<?php
session_start();
header('Content-Type: application/json');

// Sécurité : Vérification de session admin
if (empty($_SESSION["admin"])) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "Non autorisé"]);
    exit();
}
// Connexion à la BDD
    require_once("./connexion_bdd.php");

try {
    
    if 
    
} catch (PDO $th) {
    http_response_code(500);
    if ($e->getCode() === '23000') {
        // Doublon
        echo json_encode(["success" => false, "message" => "Ce plat existe déjà. Vous pouvez l'afficher ou le masquer en cliquant sur le petit bouton"]);
    } else {
        // Log interne seulement, message neutre
        error_log("Erreur admin_save_suggestions.php : " . $e->getMessage());
        echo json_encode(["success" => false, "message" => "Erreur interne. Veuillez réessayer."]);
    }    //throw $th;
}