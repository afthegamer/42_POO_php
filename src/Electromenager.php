<?php

class Electromenager extends Produit {
    private int $garantie = 0; // Garantie en mois

    /**
     * Sauvegarde un produit électroménager dans la base.
     */
    public function sauvegarder(): void {
        parent::sauvegarder();
        if ($this->id && $this->garantie) {
            $pdo = (new Database)->getConnection();
            $query = "UPDATE produits SET garantie = :garantie WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':garantie' => $this->garantie,
                ':id' => $this->id,
            ]);
        }
    }

    /**
     * Définit la garantie.
     */
    public function setGarantie(int $garantie): void {
        $this->garantie = $garantie;
    }

    /**
     * Retourne la garantie.
     */
    public function getGarantie(): int {
        return $this->garantie;
    }
}
