<?php
require_once '../autoload.php';

$message = '';
try {
    $pdo = (new Database)->getConnection();
    $query = "
        SELECT commandes.id AS commande_id, commandes.date_commande, clients.nom AS client_nom,
               GROUP_CONCAT(CONCAT(produits.nom, ' (', commande_produits.quantite, ')') SEPARATOR ', ') AS produits
        FROM commandes
        INNER JOIN clients ON commandes.client_id = clients.id
        INNER JOIN commande_produits ON commandes.id = commande_produits.commande_id
        INNER JOIN produits ON commande_produits.produit_id = produits.id
        GROUP BY commandes.id
        ORDER BY commandes.date_commande DESC
    ";
    $stmt = $pdo->query($query);
    $commandes = $stmt->fetchAll();
} catch (Exception $e) {
    $message = "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="text-center">Liste des Commandes</h1>
    <?php if ($message): ?>
        <div class="alert alert-danger" role="alert"><?= $message ?></div>
    <?php endif; ?>

    <table class="table table-striped">
        <thead class="table-dark">
        <tr>
            <th>Commande</th>
            <th>Client</th>
            <th>Date</th>
            <th>Produits</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($commandes)): ?>
            <?php foreach ($commandes as $commande): ?>
                <tr>
                    <td>#<?= $commande['commande_id'] ?></td>
                    <td><?= $commande['client_nom'] ?></td>
                    <td><?= $commande['date_commande'] ?></td>
                    <td><?= $commande['produits'] ?></td>
                    <td>
                        <a href="delete_order.php?id=<?= $commande['commande_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">Aucune commande trouvée.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    <a href="index.php" class="btn btn-secondary mt-3">Retour au menu principal</a>
</div>
</body>
</html>
