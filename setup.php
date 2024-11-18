<?php

require_once 'autoload.php';

try {
    $pdo = (new Database)->getConnection();

    // Création de la table `clients`
    $queryClients = "
        CREATE TABLE IF NOT EXISTS clients (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL
        );
    ";
    $pdo->exec($queryClients);

    // Ajout d'un client par défaut avec un ID fixe
    $queryInsertClient = "
        INSERT INTO clients (id, nom, email) 
        VALUES (1, 'Client Test', 'test@example.com')
        ON DUPLICATE KEY UPDATE nom = VALUES(nom), email = VALUES(email);
    ";
    $pdo->exec($queryInsertClient);

    // Création de la table `produits`
    $queryProduits = "
        CREATE TABLE IF NOT EXISTS produits (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(255) NOT NULL,
            prix INT NOT NULL, -- En centimes
            quantite INT NOT NULL,
            categorie VARCHAR(50) NOT NULL,
            date_expiration DATE NULL,
            taille VARCHAR(10) NULL,
            garantie INT NULL
        );
    ";
    $pdo->exec($queryProduits);

    // Ajout d'un produit par défaut avec un ID fixe
    $queryInsertProduit = "
        INSERT INTO produits (id, nom, prix, quantite, categorie) 
        VALUES (1, 'Produit Test', 1000, 50, 'Electromenager')
        ON DUPLICATE KEY UPDATE nom = VALUES(nom), prix = VALUES(prix), quantite = VALUES(quantite), categorie = VALUES(categorie);
    ";
    $pdo->exec($queryInsertProduit);

    // Création de la table `commandes`
    $queryCommandes = "
        CREATE TABLE IF NOT EXISTS commandes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            client_id INT NOT NULL,
            date_commande DATETIME NOT NULL,
            FOREIGN KEY (client_id) REFERENCES clients(id)
        );
    ";
    $pdo->exec($queryCommandes);

    // Création de la table `commande_produits`
    $queryCommandeProduits = "
        CREATE TABLE IF NOT EXISTS commande_produits (
            commande_id INT NOT NULL,
            produit_id INT NOT NULL,
            quantite INT NOT NULL,
            FOREIGN KEY (commande_id) REFERENCES commandes(id),
            FOREIGN KEY (produit_id) REFERENCES produits(id),
            PRIMARY KEY (commande_id, produit_id)
        );
    ";
    $pdo->exec($queryCommandeProduits);

    echo "Toutes les tables ont été créées avec succès, et des entrées par défaut avec des IDs fixes ont été ajoutées.\n";
} catch (Exception $e) {
    echo "Erreur lors de la création des tables : " . $e->getMessage() . "\n";
}
