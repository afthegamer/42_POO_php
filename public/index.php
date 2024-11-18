<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Magasin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1 class="text-center">Gestion de Magasin</h1>
    <div class="row">
        <div class="col-md-6">
            <h2>Actions sur les Produits</h2>
            <ul class="list-group">
                <li class="list-group-item"><a href="add_product.php" class="btn btn-success">Ajouter un produit</a></li>
                <li class="list-group-item"><a href="list_products.php" class="btn btn-primary">Lister les produits</a></li>
            </ul>
        </div>
        <div class="col-md-6">
            <h2>Actions sur les Clients</h2>
            <ul class="list-group">
                <li class="list-group-item"><a href="add_client.php" class="btn btn-success">Ajouter un client</a></li>
                <li class="list-group-item"><a href="list_clients.php" class="btn btn-primary">Lister les clients</a></li>
            </ul>
        </div>
    </div>
    <h2 class="mt-5">Actions sur les Commandes</h2>
    <ul class="list-group">
        <li class="list-group-item"><a href="place_order.php" class="btn btn-success">Passer une commande</a></li>
        <li class="list-group-item"><a href="list_orders.php" class="btn btn-primary">Lister les commandes</a></li>
    </ul>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
