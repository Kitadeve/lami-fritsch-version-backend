<?php
//session admin
require_once("./admin_session.php");

// Connexion à la bdd
require_once("./connexion_bdd.php");

try {
    $id = $_POST['id'] ?? null;
    $action = $_POST['action'] ?? '';

    if (
        $action === 'modifier' && $id &&
        isset($_POST['nouveau_nom'], $_POST['nouveau_prix'], $_POST['nouvelle_description'], $_POST['nouvelle_categorie'], $_POST['nouvel_ordre'])
    ) {
        $nom = trim($_POST['nouveau_nom']);
        $prix = str_replace(',', '.', trim($_POST['nouveau_prix']));
        $description = trim($_POST['nouvelle_description']);
        $categorie = trim($_POST['nouvelle_categorie']);
        $ordre = (int)$_POST['nouvel_ordre'];
        if (
            $nom !== '' &&
            preg_match('/^\d{1,2}\.\d{2}$/', $prix) &&
            mb_strlen($description) <= 255 &&
            in_array($categorie, ['plats', 'tartes_flambees', 'desserts'])
        ) {
            $stmt = $pdo->prepare("UPDATE carte SET ordre = ordre + 1 WHERE categorie = :categorie AND ordre >= :ordre AND id != :id");
            $stmt->execute([
                ':categorie' => $categorie,
                ':ordre' => $ordre,
                ':id' => $id
            ]);

            $stmt = $pdo->prepare("UPDATE carte SET nom = :nom, description = :description, prix = :prix, categorie = :categorie, ordre = :ordre WHERE id = :id");
            $stmt->execute([
                ':nom' => $nom,
                ':description' => $description,
                ':prix' => $prix,
                ':categorie' => $categorie,
                ':ordre' => $ordre,
                ':id' => $id
            ]);
        }
    } elseif ($action === 'supprimer' && $id) {
        $stmt = $pdo->prepare("DELETE FROM carte WHERE id = :id");
        $stmt->execute([':id' => $id]);
    } elseif ($action === 'masquer' && $id) {
        $stmt = $pdo->prepare("UPDATE carte SET visible = 0 WHERE id = :id");
        $stmt->execute([':id' => $id]);
    } elseif ($action === 'afficher' && $id) {
        $stmt = $pdo->prepare("UPDATE carte SET visible = 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    $pdo = null;

} catch (PDOException $e) {
    error_log($e->getMessage());
    // Pas d'affichage HTML ici, juste log
}

header('Location: admin_gestion.php');
exit();
?>