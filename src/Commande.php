<?php

class Commande {
    private ?int $id = null;
    private int $clientId;
    private string $dateCommande;
    private array $produits = []; // Tableau associatif : ['produit_id' => 'quantité']

    /**
     * Constructeur : charge une commande depuis la base si un ID est fourni.
     */
    public function __construct(?int $id = null) {
        if ($id !== null) {
            $this->chargerDepuisBase($id);
        } else {
            $this->dateCommande = date('Y-m-d H:i:s'); // Par défaut, commande à la date actuelle
        }
    }

    /**
     * Charge les données d'une commande depuis la base.
     */
    private function chargerDepuisBase(int $id): void {
        $pdo = (new Database)->getConnection();
        $query = "SELECT * FROM commandes WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id' => $id]);
        $commande = $stmt->fetch();

        if ($commande) {
            $this->id = $commande['id'];
            $this->clientId = $commande['client_id'];
            $this->dateCommande = $commande['date_commande'];

            // Charger les produits associés
            $queryProduits = "SELECT produit_id, quantite FROM commande_produits WHERE commande_id = :commande_id";
            $stmtProduits = $pdo->prepare($queryProduits);
            $stmtProduits->execute([':commande_id' => $this->id]);
            $this->produits = $stmtProduits->fetchAll(PDO::FETCH_KEY_PAIR);
        } else {
            throw new Exception("Commande non trouvée avec l'ID $id.");
        }
    }

    /**
     * Ajoute un produit à la commande.
     */
    public function ajouterProduit(int $produitId, int $quantite): void {
        $pdo = (new Database)->getConnection();
        $query = "SELECT nom, quantite FROM produits WHERE id = :produit_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':produit_id' => $produitId]);
        $produit = $stmt->fetch();

        if (!$produit) {
            throw new Exception("Le produit spécifié n'existe pas.");
        }

        $produitName = $produit['nom'];
        $quantiteDisponible = $produit['quantite'];

        if ($quantite > $quantiteDisponible) {
            throw new Exception("Quantité demandée ($quantite) pour le produit '$produitName' dépasse le stock disponible ($quantiteDisponible).");
        }

        if (isset($this->produits[$produitId])) {
            $this->produits[$produitId] += $quantite;
        } else {
            $this->produits[$produitId] = $quantite;
        }
    }




    /**
     * Sauvegarde la commande dans la base (INSERT ou UPDATE).
     */
    public function sauvegarder(): void {
        $pdo = (new Database)->getConnection();

        if ($this->id !== null) {
            // Mise à jour
            $query = "UPDATE commandes SET client_id = :client_id, date_commande = :date_commande WHERE id = :id";
            $params = [
                ':client_id' => $this->clientId,
                ':date_commande' => $this->dateCommande,
                ':id' => $this->id
            ];
        } else {
            // Insertion
            $query = "INSERT INTO commandes (client_id, date_commande) VALUES (:client_id, :date_commande)";
            $params = [
                ':client_id' => $this->clientId,
                ':date_commande' => $this->dateCommande
            ];
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        if ($this->id === null) {
            $this->id = (int)$pdo->lastInsertId();
        }

        // Sauvegarder les produits associés
        $this->sauvegarderProduits();
    }

    /**
     * Sauvegarde les produits associés à la commande dans la table `commande_produits`.
     */
    private function sauvegarderProduits(): void {
        $pdo = (new Database)->getConnection();

        // Supprimer les produits existants pour cette commande
        $queryDelete = "DELETE FROM commande_produits WHERE commande_id = :commande_id";
        $stmtDelete = $pdo->prepare($queryDelete);
        $stmtDelete->execute([':commande_id' => $this->id]);

        // Ajouter les nouveaux produits
        $queryInsert = "INSERT INTO commande_produits (commande_id, produit_id, quantite) VALUES (:commande_id, :produit_id, :quantite)";
        $stmtInsert = $pdo->prepare($queryInsert);

        foreach ($this->produits as $produitId => $quantite) {
            // Vérification du stock avant l'insertion
            $queryStock = "SELECT nom, quantite FROM produits WHERE id = :produit_id";
            $stmtStock = $pdo->prepare($queryStock);
            $stmtStock->execute([':produit_id' => $produitId]);
            $stock = $stmtStock->fetch();

            $produitName = $stock['nom'];
            if ($stock['quantite'] < $quantite) {
                throw new Exception("Stock insuffisant pour le produit '$produitName'.");
            }

            // Insérer le produit dans la commande
            $stmtInsert->execute([
                ':commande_id' => $this->id,
                ':produit_id' => $produitId,
                ':quantite' => $quantite,
            ]);

            // Mettre à jour le stock
            $queryUpdateStock = "UPDATE produits SET quantite = quantite - :quantite WHERE id = :produit_id";
            $stmtUpdate = $pdo->prepare($queryUpdateStock);
            $stmtUpdate->execute([
                ':quantite' => $quantite,
                ':produit_id' => $produitId,
            ]);
        }
    }



    /**
     * Supprime la commande de la base.
     */
    public function supprimer(): void {
        if ($this->id !== null) {
            $pdo = (new Database)->getConnection();

            // Supprimer les produits associés
            $queryProduits = "DELETE FROM commande_produits WHERE commande_id = :commande_id";
            $stmtProduits = $pdo->prepare($queryProduits);
            $stmtProduits->execute([':commande_id' => $this->id]);

            // Supprimer la commande
            $query = "DELETE FROM commandes WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':id' => $this->id]);

            $this->id = null;
        } else {
            throw new Exception("Impossible de supprimer : Commande non enregistrée.");
        }
    }

    /**
     * Calcule le total de la commande en centimes.
     */
    public function getTotalCommande(): int {
        $total = 0;
        foreach ($this->produits as $produitId => $quantite) {
            $produit = new Produit($produitId);
            $total += $produit->getPrix() * $quantite;
        }
        return $total;
    }

    /**
     * Getters et setters.
     */
    public function getId(): ?int {
        return $this->id;
    }

    public function getClientId(): int {
        return $this->clientId;
    }

    public function setClientId(int $clientId): void {
        $this->clientId = $clientId;
    }

    public function getDateCommande(): string {
        return $this->dateCommande;
    }

    public function setDateCommande(string $dateCommande): void {
        $this->dateCommande = $dateCommande;
    }

    public function getProduits(): array {
        return $this->produits;
    }
}
