<?php
require_once '../autoload.php';

if (isset($_GET['id'])) {
    try {
        $pdo = (new Database)->getConnection();

        // Vérifier si le client a des commandes
        $queryCheck = "SELECT COUNT(*) AS total FROM commandes WHERE client_id = :client_id";
        $stmtCheck = $pdo->prepare($queryCheck);
        $stmtCheck->execute([':client_id' => $_GET['id']]);
        $result = $stmtCheck->fetch();

        if ($result['total'] > 0) {
            throw new Exception("Impossible de supprimer le client : il a une ou plusieurs commandes associées.");
        }

        // Supprimer le client
        $client = new Client((int)$_GET['id']);
        $client->supprimer();
        $message = "Client supprimé avec succès.";
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
} else {
    $message = "Aucun client spécifié.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="text-center">Supprimer un Client</h1>
    <div class="alert alert-info"><?= $message ?></div>
    <a href="list_clients.php" class="btn btn-primary mt-3">Retour à la liste des clients</a>
</div>
</body>
</html>
