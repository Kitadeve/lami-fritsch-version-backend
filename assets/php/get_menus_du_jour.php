<?php
$pdo = new PDO("mysql:host=127.0.0.1;port=3307;dbname=restaurant;charset=utf8", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "SELECT m.jour, e.nom AS entree, p.nom AS plat
        FROM menus_du_jour m
        JOIN entrees e ON m.entree_id = e.id
        JOIN plats p ON m.plat_id = p.id
        ORDER BY FIELD(m.jour,'Mercredi','Jeudi','Vendredi')";
$menus = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($menus);