<?php
require_once '../autoload.php';

if (isset($_GET['id'])) {
    try {
        $commande = new Commande((int)$_GET['id']);
        $commande->supprimer();
        $message = "Commande supprimée avec succès.";
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
} else {
    $message = "Aucune commande spécifiée.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer une Commande</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="text-center">Supprimer une Commande</h1>
    <div class="alert alert-info"><?= $message ?></div>
    <a href="list_orders.php" class="btn btn-primary mt-3">Retour à la liste des commandes</a>
</div>
</body>
</html>
