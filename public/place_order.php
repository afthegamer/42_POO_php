<?php
require_once '../autoload.php';

$message = '';

try {
    $clients = (new Client)->getTousLesClients();
    $produits = (new Produit)->getTousLesProduits();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $commande = new Commande();
        $commande->setClientId((int) $_POST['client_id']);

        foreach ($_POST['produits'] as $produitId => $quantite) {
            if ((int) $quantite > 0) {
                $commande->ajouterProduit((int) $produitId, (int) $quantite);
            }
        }

        $commande->sauvegarder();
        $message = "Commande créée avec succès.";
    }
} catch (Exception $e) {
    $message = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passer une Commande</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="text-center">Passer une Commande</h1>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="client_id" class="form-label">Client</label>
            <select class="form-select" id="client_id" name="client_id" required>
                <?php foreach ($clients as $client): ?>
                    <option value="<?= $client['id'] ?>"><?= $client['nom'] ?> (<?= $client['email'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <h3>Produits</h3>
        <?php foreach ($produits as $produit): ?>
            <div class="mb-3">
                <label>
                    <?= $produit['nom'] ?> (<?= $produit['prix'] / 100 ?> €) :
                    <input type="number" class="form-control" name="produits[<?= $produit['id'] ?>]" min="0" step="1">
                </label>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-primary">Créer la commande</button>
    </form>
    <a href="index.php" class="btn btn-secondary mt-3">Retour au menu principal</a>
</div>
</body>
</html>
