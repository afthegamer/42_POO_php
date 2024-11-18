<?php

class Produit {
    protected ?int $id = null;
    protected string $nom = '';
    protected int $prix = 0; // En centimes
    protected int $quantite = 0;
    protected string $categorie = '';

    /**
     * Constructeur : charge un produit depuis la base si un ID est fourni.
     */
    public function __construct(?int $id = null) {
        if ($id !== null) {
            $this->chargerDepuisBase($id);
        }
    }

    /**
     * Charge les données d'un produit depuis la base.
     */
    protected function chargerDepuisBase(int $id): void {
        $pdo = (new Database)->getConnection();
        $query = "SELECT * FROM produits WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id' => $id]);
        $produit = $stmt->fetch();

        if ($produit) {
            $this->id = $produit['id'];
            $this->nom = $produit['nom'];
            $this->prix = $produit['prix'];
            $this->quantite = $produit['quantite'];
            $this->categorie = $produit['categorie'];
        } else {
            throw new Exception("Produit non trouvé avec l'ID $id.");
        }
    }

    /**
     * Sauvegarde le produit dans la base (INSERT ou UPDATE).
     */
    public function sauvegarder(): void {
        $pdo = (new Database)->getConnection();

        if ($this->id !== null) {
            // Mise à jour
            $query = "UPDATE produits 
                      SET nom = :nom, prix = :prix, quantite = :quantite, categorie = :categorie 
                      WHERE id = :id";
            $params = [
                ':nom' => $this->nom,
                ':prix' => $this->prix,
                ':quantite' => $this->quantite,
                ':categorie' => $this->categorie,
                ':id' => $this->id
            ];
        } else {
            // Insertion
            $query = "INSERT INTO produits (nom, prix, quantite, categorie) 
                      VALUES (:nom, :prix, :quantite, :categorie)";
            $params = [
                ':nom' => $this->nom,
                ':prix' => $this->prix,
                ':quantite' => $this->quantite,
                ':categorie' => $this->categorie
            ];
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        if ($this->id === null) {
            $this->id = (int)$pdo->lastInsertId();
        }
    }

    /**
     * Supprime le produit de la base.
     */
    public function supprimer(): void {
        if ($this->id !== null) {
            $pdo = (new Database)->getConnection();
            $query = "DELETE FROM produits WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':id' => $this->id]);
            $this->id = null;
        } else {
            throw new Exception("Impossible de supprimer : Produit non enregistré.");
        }
    }

    /**
     * Met à jour la quantité du produit dans la base.
     */
    public function mettreAJourQuantite(int $quantite): void {
        $this->quantite = $quantite;
        $this->sauvegarder();
    }

    /**
     * Retourne tous les produits depuis la base.
     */
    public static function getTousLesProduits(): array {
        $pdo = (new Database)->getConnection();
        $query = "SELECT * FROM produits";
        $stmt = $pdo->query($query);
        return $stmt->fetchAll();
    }

    /**
     * Getters et setters.
     */
    public function getId(): ?int {
        return $this->id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function getPrix(): int {
        return $this->prix;
    }

    public function setPrix(int $prix): void {
        $this->prix = $prix;
    }

    public function getQuantite(): int {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): void {
        $this->quantite = $quantite;
    }

    public function getCategorie(): string {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): void {
        $this->categorie = $categorie;
    }
}
