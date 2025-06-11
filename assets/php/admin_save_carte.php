<?php
session_start();
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Sécurité : Vérification de session admin
if (empty($_SESSION["admin"])) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "Non autorisé"]);
    exit();
}
// Connexion à la BDD
    require_once("./connexion_bdd.php");

try {
    if (
        !isset($_POST["cartePlat"], $_POST["cartePrix"]) ||
        trim($_POST["cartePlat"]) === "" ||
        trim($_POST["cartePrix"]) === ""
    ) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Champs manquants"]);
        exit();
    }

    // Nettoyage
    $nom = trim($_POST["cartePlat"]);
    $prix = str_replace(",", ".", trim($_POST["cartePrix"]));
    $categorie = $_POST["categorie"];

    // Vérification du nom (sécurité & longueur)
    if (mb_strlen($nom) > 255) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Nom trop long (255 caractères max)"]);
        exit();
    }

    // Vérification du prix (strict format 0.00 à 99.99)
    if (!preg_match('/^\d{1,2}(\.\d{2})$/', $prix)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Format de prix invalide (ex: 8.50)"]);
        exit();
    }

    // Insertion sécurisée
    $stmt = $pdo->prepare("INSERT INTO carte (nom, categorie, prix) VALUES (:nom, :categorie, :prix)");
    $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
    $stmt->bindValue(':prix', $prix, PDO::PARAM_STR);
    $stmt->bindValue(':categorie', $categorie, PDO::PARAM_STR);
    $stmt->execute();
    
    // ...after inserting into carte...
    $carte_id = $pdo->lastInsertId();

    if (!empty($_POST['carteDescriptions']) && is_array($_POST['carteDescriptions'])) {
        $stmtDesc = $pdo->prepare("INSERT INTO carte_descriptions (carte_id, description) VALUES (:carte_id, :description)");
        foreach ($_POST['carteDescriptions'] as $desc) {
            $desc = trim($desc);
            if ($desc !== '') {
                $stmtDesc->execute([
                    ':carte_id' => $carte_id,
                    ':description' => $desc
                ]);
            }
        }
    }  

    echo json_encode(["success" => true, "message" => "Plat enregistrée avec succès !"]);

    $pdo = null;
} catch (PDOException $e) {
    http_response_code(500);
    if ($e->getCode() === '23000') {
        // Doublon
        echo json_encode(["success" => false, "message" => "Ce plat existe déjà. Vous pouvez l'afficher ou le masquer en cliquant sur le petit bouton"]);
    } else {
        // Log interne seulement, message neutre
        error_log("Erreur admin_save_suggestions.php : " . $e->getMessage());
        echo json_encode(["success" => false, "message" => "Erreur interne. Veuillez réessayer."]);
    }  
    exit();
}