
<?php
$pdo = new PDO("mysql:host=127.0.0.1;port=3307;dbname=restaurant;charset=utf8", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "SELECT id, nom, prix FROM suggestions ORDER BY id DESC";
$suggestions = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($suggestions);
?>