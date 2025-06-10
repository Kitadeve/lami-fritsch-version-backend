<?php
session_start();
header('Content-Type: application/json');

if (empty($_SESSION['admin'])) {
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit();
}

require_once("./connexion_bdd.php");

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'], $data['visible'])) {
        echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
        exit();
    }

    $stmt = $pdo->prepare("UPDATE carte SET visible = :visible WHERE id = :id");
    $stmt->execute([':visible' => $data['visible'], ':id' => $data['id']]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur serveur : ' . $e->getMessage()]);
}
?>