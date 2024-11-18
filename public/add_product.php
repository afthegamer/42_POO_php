<?php
require_once '../autoload.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $produit = new Produit();
        $produit->setNom($_POST['nom']);
        $produit->setPrix((int) ($_POST['prix'] * 100)); // Convertir en centimes
        $produit->setQuantite((int) $_POST['quantite']);
        $produit->setCategorie($_POST['categorie']);
        $produit->sauvegarder();
        $message = "Produit ajouté avec succès.";
    } catch (Exception $e) {
        $message = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Produit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="text-center">Ajouter un Produit</h1>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        <div class="mb-3">
            <label for="prix" class="form-label">Prix (€)</label>
            <input type="number" class="form-control" id="prix" name="prix" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="quantite" class="form-label">Quantité</label>
            <input type="number" class="form-control" id="quantite" name="quantite" required>
        </div>
        <div class="mb-3">
            <label for="categorie" class="form-label">Catégorie</label>
            <select class="form-select" id="categorie" name="categorie" required>
                <option value="Alimentaire">Alimentaire</option>
                <option value="Textile">Textile</option>
                <option value="Electromenager">Electroménager</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
    <a href="index.php" class="btn btn-secondary mt-3">Retour au menu principal</a>
</div>
</body>
</html>
