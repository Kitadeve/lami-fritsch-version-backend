<?php
//session admin
require_once("./admin_session.php");

// Connexion à la bdd
require_once("./connexion_bdd.php");

file_put_contents('debug.txt', print_r($_POST, true));

try {
    $id = $_POST['id'] ?? null;
    $action = $_POST['action'] ?? '';

    if (
        $action === 'modifier' && $id &&
        isset($_POST['nouveau_nom'], $_POST['nouveau_prix'], $_POST['nouvelle_categorie'], $_POST['nouvel_ordre'])
    ) {
        $nom = trim($_POST['nouveau_nom']);
        $prix = str_replace(',', '.', trim($_POST['nouveau_prix']));
        $categorie = trim($_POST['nouvelle_categorie']);
        $ordre = (int)$_POST['nouvel_ordre'];
        if (
            $nom !== '' &&
            preg_match('/^\d{1,2}\.\d{2}$/', $prix) &&
            in_array($categorie, ['plats', 'tartes_flambees', 'desserts'])
        ) {
            $stmt = $pdo->prepare("UPDATE carte SET ordre = ordre + 1 WHERE categorie = :categorie AND ordre >= :ordre AND id != :id");
            $stmt->execute([
                ':categorie' => $categorie,
                ':ordre' => $ordre,
                ':id' => $id
            ]);

            $stmt = $pdo->prepare("UPDATE carte SET nom = :nom, prix = :prix, categorie = :categorie, ordre = :ordre WHERE id = :id");
            $stmt->execute([
                ':nom' => $nom,
                ':prix' => $prix,
                ':categorie' => $categorie,
                ':ordre' => $ordre,
                ':id' => $id
            ]);

            $description_ids = $_POST['description_ids'] ?? [];
            $descriptions = $_POST['descriptions'] ?? [];
            $ids_to_keep = [];

            foreach ($descriptions as $i => $desc) {
                $desc = trim($desc);
                $desc_id = $description_ids[$i] ?? null;

                if ($desc_id && $desc !== '') {
                    // Update
                    $stmtUpdate = $pdo->prepare("UPDATE carte_descriptions SET description = :description WHERE id = :id");
                    $stmtUpdate->execute([
                        ':description' => $desc,
                        ':id' => $desc_id
                    ]);
                    $ids_to_keep[] = $desc_id;
                } elseif ($desc !== '') {
                    // Insert new
                    $stmtInsert = $pdo->prepare("INSERT INTO carte_descriptions (carte_id, description) VALUES (:carte_id, :description)");
                    if (!$stmtInsert->execute([
                        ':carte_id' => $id,
                        ':description' => $desc
                    ])) {
                        error_log('Insert failed: ' . print_r($stmtInsert->errorInfo(), true));
                    }
                    // Optionally, get the new ID if you want to use it
                    $ids_to_keep[] = $pdo->lastInsertId();
                } elseif ($desc_id && $desc === '') {
                    // Delete if emptied
                    $stmtDel = $pdo->prepare("DELETE FROM carte_descriptions WHERE id = :id");
                    $stmtDel->execute([':id' => $desc_id]);
                }
            }
            // Optionally, delete any descriptions in DB not present in the form
            if ($ids_to_keep) {
                $in = implode(',', array_map('intval', $ids_to_keep));
                $pdo->query("DELETE FROM carte_descriptions WHERE carte_id = $id AND id NOT IN ($in)");
            }
        }
    } elseif ($action === 'supprimer' && $id) {
        $stmt = $pdo->prepare("DELETE FROM carte WHERE id = :id");
        $stmt->execute([':id' => $id]);
    } elseif ($action === 'masquer' && $id) {
        $stmt = $pdo->prepare("UPDATE carte SET visible = 0 WHERE id = :id");
        $stmt->execute([':id' => $id]);
        exit; // For AJAX, no redirect
    } elseif ($action === 'afficher' && $id) {
        $stmt = $pdo->prepare("UPDATE carte SET visible = 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);
        exit;
    }

    $pdo = null;

} catch (PDOException $e) {
    error_log($e->getMessage());
    // Pas d'affichage HTML ici, juste log
}

header('Location: admin_gestion.php');
exit();
?>