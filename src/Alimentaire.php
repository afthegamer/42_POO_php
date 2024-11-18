<?php

class Alimentaire extends Produit {
    private ?string $dateExpiration = null;

    /**
     * Sauvegarde un produit alimentaire dans la base.
     */
    public function sauvegarder(): void {
        parent::sauvegarder();
        if ($this->id && $this->dateExpiration) {
            $pdo = (new Database)->getConnection();
            $query = "UPDATE produits SET date_expiration = :dateExpiration WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':dateExpiration' => $this->dateExpiration,
                ':id' => $this->id,
            ]);
        }
    }

    /**
     * Définit la date d'expiration.
     */
    public function setDateExpiration(string $dateExpiration): void {
        $this->dateExpiration = $dateExpiration;
    }

    /**
     * Retourne la date d'expiration.
     */
    public function getDateExpiration(): ?string {
        return $this->dateExpiration;
    }
}
