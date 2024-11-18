<?php
require_once '../autoload.php';

try {
    $produits = (new Produit)->getTousLesProduits();
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Produits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="text-center">Liste des Produits</h1>
    <table class="table table-striped">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prix (€)</th>
            <th>Quantité</th>
            <th>Catégorie</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($produits)): ?>
            <?php foreach ($produits as $produit): ?>
                <tr>
                    <td><?= $produit['id'] ?></td>
                    <td><?= $produit['nom'] ?></td>
                    <td><?= $produit['prix'] / 100 ?></td>
                    <td><?= $produit['quantite'] ?></td>
                    <td><?= $produit['categorie'] ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $produit['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="delete_product.php?id=<?= $produit['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">Aucun produit trouvé.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    <a href="index.php" class="btn btn-secondary mt-3">Retour au menu principal</a>
</div>
</body>
</html>
