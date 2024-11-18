<?php

class Database {
    const HOST = 'db';
    const USER = 'lamp_user';
    const PASSWORD = 'lamp_password';
    const DBNAME = 'gestion_magasin';

    private static $connection = null;

    /**
     * Retourne une connexion PDO. Crée la base si elle n'existe pas.
     */
    public static function getConnection() {
        if (self::$connection === null) {
            try {
                // Connexion au serveur MySQL
                $pdo = new PDO(
                    "mysql:host=" . self::HOST,
                    self::USER,
                    self::PASSWORD
                );
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Vérifie si la base existe
                $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :dbname";
                $stmt = $pdo->prepare($query);
                $stmt->execute([':dbname' => self::DBNAME]);

                // Crée la base si elle n'existe pas
                if ($stmt->rowCount() === 0) {
                    $pdo->exec("CREATE DATABASE `" . self::DBNAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
                    echo "Base de données '" . self::DBNAME . "' créée avec succès.\n";
                } else {
                    echo "Base de données '" . self::DBNAME . "' existe déjà.\n";
                }

                // Connexion à la base
                self::$connection = new PDO(
                    "mysql:host=" . self::HOST . ";dbname=" . self::DBNAME,
                    self::USER,
                    self::PASSWORD
                );
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                echo "Connexion réussie à la base de données.\n";

            } catch (PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage() . "\n");
            }
        }

        return self::$connection;
    }
}
