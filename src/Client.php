<?php

class Client {
    private ?int $id = null;
    private string $nom = '';
    private string $email = '';

    /**
     * Constructeur : charge un client depuis la base si un ID est fourni.
     */
    public function __construct(?int $id = null) {
        if ($id !== null) {
            $this->chargerDepuisBase($id);
        }
    }

    /**
     * Charge les données d'un client depuis la base.
     */
    private function chargerDepuisBase(int $id): void {
        $pdo = (new Database)->getConnection();
        $query = "SELECT * FROM clients WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id' => $id]);
        $client = $stmt->fetch();

        if ($client) {
            $this->id = $client['id'];
            $this->nom = $client['nom'];
            $this->email = $client['email'];
        } else {
            throw new Exception("Client non trouvé avec l'ID $id.");
        }
    }

    /**
     * Sauvegarde le client dans la base (INSERT ou UPDATE).
     */
    public function sauvegarder(): void {
        $pdo = (new Database)->getConnection();

        if ($this->id !== null) {
            // Mise à jour
            $query = "UPDATE clients SET nom = :nom, email = :email WHERE id = :id";
            $params = [
                ':nom' => $this->nom,
                ':email' => $this->email,
                ':id' => $this->id
            ];
        } else {
            // Insertion
            $query = "INSERT INTO clients (nom, email) VALUES (:nom, :email)";
            $params = [
                ':nom' => $this->nom,
                ':email' => $this->email
            ];
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        if ($this->id === null) {
            $this->id = (int)$pdo->lastInsertId();
        }
    }

    /**
     * Supprime le client de la base.
     */
    public function supprimer(): void {
        if ($this->id !== null) {
            $pdo = (new Database)->getConnection();
            $query = "DELETE FROM clients WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':id' => $this->id]);
            $this->id = null;
        } else {
            throw new Exception("Impossible de supprimer : Client non enregistré.");
        }
    }

    /**
     * Retourne tous les clients depuis la base.
     */
    public static function getTousLesClients(): array {
        $pdo = (new Database)->getConnection();
        $query = "SELECT * FROM clients";
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

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }
}
