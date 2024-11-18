<?php
require_once '../autoload.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $produit = new Produit((int)$_POST['id']);
        $produit->setNom($_POST['nom']);
        $produit->setPrix((int) ($_POST['prix'] * 100));
        $produit->setQuantite((int) $_POST['quantite']);
        $produit->setCategorie($_POST['categorie']);
        $produit->sauvegarder();
        $message = "Produit mis à jour avec succès.";
    } catch (Exception $e) {
        $message = "Erreur : " . $e->getMessage();
    }
} elseif (isset($_GET['id'])) {
    try {
        $produit = new Produit((int)$_GET['id']);
    } catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }
} else {
    die("Aucun produit spécifié.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Produit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="text-center">Modifier un Produit</h1>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $produit->getId() ?>">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" value="<?= $produit->getNom() ?>" required>
        </div>
        <div class="mb-3">
            <label for="prix" class="form-label">Prix (€)</label>
            <input type="number" class="form-control" id="prix" name="prix" value="<?= $produit->getPrix() / 100 ?>" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="quantite" class="form-label">Quantité</label>
            <input type="number" class="form-control" id="quantite" name="quantite" value="<?= $produit->getQuantite() ?>" required>
        </div>
        <div class="mb-3">
            <label for="categorie" class="form-label">Catégorie</label>
            <select class="form-select" id="categorie" name="categorie" required>
                <option value="Alimentaire" <?= $produit->getCategorie() === 'Alimentaire' ? 'selected' : '' ?>>Alimentaire</option>
                <option value="Textile" <?= $produit->getCategorie() === 'Textile' ? 'selected' : '' ?>>Textile</option>
                <option value="Electromenager" <?= $produit->getCategorie() === 'Electromenager' ? 'selected' : '' ?>>Electroménager</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Modifier</button>
    </form>
    <a href="list_products.php" class="btn btn-secondary mt-3">Retour à la liste des produits</a>
</div>
</body>
</html>
