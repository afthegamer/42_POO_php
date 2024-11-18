<?php
require_once '../autoload.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $client = new Client((int)$_POST['id']);
        $client->setNom($_POST['nom']);
        $client->setEmail($_POST['email']);
        $client->sauvegarder();
        $message = "Client mis à jour avec succès.";
    } catch (Exception $e) {
        $message = "Erreur : " . $e->getMessage();
    }
} elseif (isset($_GET['id'])) {
    try {
        $client = new Client((int)$_GET['id']);
    } catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }
} else {
    die("Aucun client spécifié.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="text-center">Modifier un Client</h1>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $client->getId() ?>">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" value="<?= $client->getNom() ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $client->getEmail() ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Modifier</button>
    </form>
    <a href="index.php" class="btn btn-secondary mt-3">Retour au menu principal</a>
</div>
</body>
</html>
