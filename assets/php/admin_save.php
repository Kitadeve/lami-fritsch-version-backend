<?php
//session admin
require_once("./admin_session.php");
 // Connexion à la bdd
require_once("./connexion_bdd.php");

try {
    $jours = ['Mercredi','Jeudi','Vendredi'];

    foreach ($jours as $jour) {
        $entree_id = $_POST['entree_existante_id'][$jour] ?? null;
        $plat_id = $_POST['plat_existant_id'][$jour] ?? null;
        $entree_nouvelle = trim($_POST['entree_nouvelle'][$jour] ?? '');
        $plat_nouveau = trim($_POST['plat_nouveau'][$jour] ?? '');


        // Gérer l'entrée
        if (!$entree_id && $entree_nouvelle !== "") {
            $stmt = $pdo->prepare("INSERT IGNORE INTO entrees(nom) VALUES (:nom)");
            $stmt->bindValue(":nom", $entree_nouvelle, PDO::PARAM_STR);
            $stmt->execute();
            $entree_id = $pdo->lastInsertId();

            if (!$entree_id) {
                $stmt = $pdo->prepare("SELECT id FROM entrees WHERE nom = :nom");
                $stmt->bindValue(":nom", $entree_nouvelle, PDO::PARAM_STR);
                $stmt->execute();
                $entree_id = $stmt->fetchColumn();
            }
        }

        // Gérer le plat
        if (!$plat_id && $plat_nouveau !=="") {
            $stmt = $pdo->prepare("INSERT IGNORE INTO plats(nom) VALUES (:nom)");
            $stmt->bindValue(":nom", $plat_nouveau, PDO::PARAM_STR);
            $stmt->execute();
            $plat_id = $pdo->lastInsertId();

            if (!$plat_id) {
                $stmt = $pdo->prepare("SELECT id FROM plats WHERE nom = :nom");
                $stmt->bindValue(':nom', $plat_nouveau, PDO::PARAM_STR);
                $stmt->execute();
                $plat_id = $stmt->fetchColumn();
            }
        }

                // Insertion ou mise à jour du menu du jour
        if ($entree_id && $plat_id) {
            $sql = "INSERT INTO menus_du_jour (jour, entree_id, plat_id)
                    VALUES (:jour, :entree_id, :plat_id)
                    ON DUPLICATE KEY UPDATE entree_id = :entree_id, plat_id = :plat_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':jour', $jour, PDO::PARAM_STR);
            $stmt->bindValue(':entree_id', $entree_id, PDO::PARAM_INT);
            $stmt->bindValue(':plat_id', $plat_id, PDO::PARAM_INT);
            $stmt->execute();
        } 
    }

    $pdo = null;
    // Redirection après succès
    header("Location: admin.php?success=1");
    exit();

} catch (PDOException $e) {
    // Log interne + message utilisateur neutre
    error_log("Erreur admin_save.php : " . $e->getMessage());
    http_response_code(500);
    echo "Une erreur est survenue lors de l'enregistrement.";
    exit();
}


?>



