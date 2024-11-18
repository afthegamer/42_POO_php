<?php
require_once '../autoload.php';

try {
    $clients = (new Client)->getTousLesClients();
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Clients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="text-center">Liste des Clients</h1>
    <table class="table table-striped">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($clients)): ?>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= $client['id'] ?></td>
                    <td><?= $client['nom'] ?></td>
                    <td><?= $client['email'] ?></td>
                    <td>
                        <a href="edit_client.php?id=<?= $client['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="delete_client.php?id=<?= $client['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="text-center">Aucun client trouvé.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    <a href="index.php" class="btn btn-secondary mt-3">Retour au menu principal</a>
</div>
</body>
</html>
