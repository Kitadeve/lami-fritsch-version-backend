<?php

 // Connexion à la bdd
require_once("./connexion_bdd.php");
try {
    $jours = ['Mercredi','Jeudi','Vendredi'];
foreach ($jours as $jour) {
    $entree_id = $_POST['entree_existante_id'][$jour] ?? null;
    $plat_id = $_POST['plat_existant_id'][$jour] ?? null;
    $entree_nouvelle = trim($_POST['entree_nouvelle'][$jour] ?? '');
    $plat_nouveau = trim($_POST['plat_nouveau'][$jour] ?? '');

    // if (!$jour) {
    //     header("Location: admin.php?error=1");
    //     exit();
    // }

    // Gérer l'entrée
    if (!$entree_id && $entree_nouvelle) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO entrees(nom) VALUES (:nom)");
        $stmt->execute([':nom' => $entree_nouvelle]);
        $entree_id = $pdo->lastInsertId();
        if (!$entree_id) {
            $stmt = $pdo->prepare("SELECT id FROM entrees WHERE nom = :nom");
            $stmt->execute([':nom' => $entree_nouvelle]);
            $entree_id = $stmt->fetchColumn();
        }
    }

    // Gérer le plat
    if (!$plat_id && $plat_nouveau) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO plats(nom) VALUES (:nom)");
        $stmt->execute([':nom' => $plat_nouveau]);
        $plat_id = $pdo->lastInsertId();
        if (!$plat_id) {
            $stmt = $pdo->prepare("SELECT id FROM plats WHERE nom = :nom");
            $stmt->execute([':nom' => $plat_nouveau]);
            $plat_id = $stmt->fetchColumn();
        }
    }

    if ($entree_id && $plat_id) {
        $sql = "INSERT INTO menus_du_jour (jour, entree_id, plat_id)
                VALUES (:jour, :entree_id, :plat_id)
                ON DUPLICATE KEY UPDATE entree_id = :entree_id, plat_id = :plat_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':jour' => $jour,
            ':entree_id' => $entree_id,
            ':plat_id' => $plat_id
        ]);
    } 
}

header("Location: admin.php?success=1");
exit();

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}


