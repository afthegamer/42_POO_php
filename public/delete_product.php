<?php
require_once '../autoload.php';

if (isset($_GET['id'])) {
    try {
        $pdo = (new Database)->getConnection();

        // Vérifier si le produit est utilisé dans une commande
        $queryCheck = "SELECT COUNT(*) AS total FROM commande_produits WHERE produit_id = :produit_id";
        $stmtCheck = $pdo->prepare($queryCheck);
        $stmtCheck->execute([':produit_id' => $_GET['id']]);
        $result = $stmtCheck->fetch();

        if ($result['total'] > 0) {
            throw new Exception("Impossible de supprimer le produit : il est utilisé dans une ou plusieurs commandes.");
        }

        // Supprimer le produit
        $produit = new Produit((int)$_GET['id']);
        $produit->supprimer();
        $message = "Produit supprimé avec succès.";
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
} else {
    $message = "Aucun produit spécifié.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un Produit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="text-center">Supprimer un Produit</h1>
    <div class="alert alert-info"><?= $message ?></div>
    <a href="list_products.php" class="btn btn-primary mt-3">Retour à la liste des produits</a>
</div>
</body>
</html>
