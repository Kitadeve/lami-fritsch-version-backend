<?php
// filepath: c:\xampp\htdocs\lami-fritsch-version-backend\assets\php\contact_save.php
//session admin
require_once("./admin_session.php");
header('Content-Type: application/json');

require_once("./connexion_bdd.php");

try {
// Vérifie que le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les données JSON envoyées par le JS
    $data = json_decode(file_get_contents('php://input'), true);

    $lastName = trim($data['lastName'] ?? '');
    $firstName = trim($data['firstName'] ?? '');
    $email = trim($data['email'] ?? '');
    $phoneNumber = trim($data['phoneNumber'] ?? '');
    $dateTime = trim($data['dateTime'] ?? '');
    $eventType = trim($data['eventType'] ?? '');
    $message = trim($data['message'] ?? '');
    $subscribeNews = !empty($data['check']) ? 1 : 0;

    // Prépare la requête d'insertion
    $stmt = $pdo->prepare("INSERT INTO contact_messages 
        (lastName, firstName, email, phoneNumber, dateTime, eventType, message, subscribeNews)
        VALUES (:lastName, :firstName, :email, :phoneNumber, :dateTime, :eventType, :message, :subscribeNews)");

    $ok = $stmt->execute([
        ':lastName' => $lastName,
        ':firstName' => $firstName,
        ':email' => $email,
        ':phoneNumber' => $phoneNumber,
        ':dateTime' => $dateTime ? date('Y-m-d H:i:s', strtotime($dateTime)) : null,
        ':eventType' => $eventType,
        ':message' => $message,
        ':subscribeNews' => $subscribeNews
    ]);

    echo json_encode(['success' => $ok]);
    exit();
} else {
    echo json_encode(['success' => false]);
    exit();
}

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
    error_log($e->getMessage());
    echo "<script>console.error(" . json_encode($e->getMessage()) . ");</script>";
    echo "<div style='color:red;'>Erreur : " . htmlspecialchars($e->getMessage()) . "</div>";
}

?>