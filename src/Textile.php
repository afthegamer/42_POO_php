<?php

class Textile extends Produit {
    private string $taille = '';

    /**
     * Sauvegarde un produit textile dans la base.
     */
    public function sauvegarder(): void {
        parent::sauvegarder();
        if ($this->id && $this->taille) {
            $pdo = (new Database)->getConnection();
            $query = "UPDATE produits SET taille = :taille WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':taille' => $this->taille,
                ':id' => $this->id,
            ]);
        }
    }

    /**
     * Définit la taille.
     */
    public function setTaille(string $taille): void {
        $this->taille = $taille;
    }

    /**
     * Retourne la taille.
     */
    public function getTaille(): string {
        return $this->taille;
    }
}
