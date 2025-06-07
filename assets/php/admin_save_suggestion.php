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
    

    // Validation des champs
    if (
        !isset($_POST["suggestion"], $_POST["prix"]) ||
        trim($_POST["suggestion"]) === "" ||
        trim($_POST["prix"]) === ""
    ) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Champs manquants"]);
        exit();
    }

    // Nettoyage
    $nom = trim($_POST["suggestion"]);
    $prix = str_replace(",", ".", trim($_POST["prix"]));

    // Vérification du nom (sécurité & longueur)
    if (mb_strlen($nom) > 255) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Nom trop long (100 caractères max)"]);
        exit();
    }

    // Vérification du prix (strict format 0.00 à 99.99)
    if (!preg_match('/^\d{1,2}(\.\d{2})$/', $prix)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Format de prix invalide (ex: 8.50)"]);
        exit();
    }

    // Insertion sécurisée
    $stmt = $pdo->prepare("INSERT INTO suggestions (nom, prix) VALUES (:nom, :prix)");
    $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
    $stmt->bindValue(':prix', $prix, PDO::PARAM_STR);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Suggestion enregistrée avec succès !"]);

    $pdo = null;

} catch (PDOException $e) {
    http_response_code(500);
    if ($e->getCode() === '23000') {
        // Doublon
        echo json_encode(["success" => false, "message" => "Cette suggestion existe déjà. Vous pouvez l'afficher ou la masquer en cliquant sur le petit bouton"]);
    } else {
        // Log interne seulement, message neutre
        error_log("Erreur admin_save_suggestions.php : " . $e->getMessage());
        echo json_encode(["success" => false, "message" => "Erreur interne. Veuillez réessayer."]);
    }
}
