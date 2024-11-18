<?php

class Database {
    // Configuration
    private const HOST = 'db';
    private const USER = 'lamp_user';
    private const PASSWORD = 'lamp_password';
    private const DBNAME = 'gestion_magasin';

    // Instance unique de connexion PDO
    private static ?PDO $connection = null;

    /**
     * Retourne une connexion PDO. Crée la base si elle n'existe pas.
     * @return PDO
     */
    public static function getConnection(): PDO {
        if (self::$connection === null) {
            try {
                // Connexion au serveur MySQL
                $pdo = new PDO(
                    "mysql:host=" . self::HOST,
                    self::USER,
                    self::PASSWORD,
                    self::getPDOOptions()
                );

                // Vérifie si la base existe
                if (!self::databaseExists($pdo)) {
                    $pdo->exec("CREATE DATABASE `" . self::DBNAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
                    self::log("Base de données '" . self::DBNAME . "' créée avec succès.");
                }

                // Connexion à la base
                self::$connection = new PDO(
                    "mysql:host=" . self::HOST . ";dbname=" . self::DBNAME,
                    self::USER,
                    self::PASSWORD,
                    self::getPDOOptions()
                );

            } catch (PDOException $e) {
                self::handleError($e);
            }
        }

        return self::$connection;
    }

    /**
     * Vérifie si la base de données existe.
     * @param PDO $pdo
     * @return bool
     */
    private static function databaseExists(PDO $pdo): bool {
        $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :dbname";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':dbname' => self::DBNAME]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Options PDO par défaut.
     * @return array
     */
    private static function getPDOOptions(): array {
        return [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT => false,
        ];
    }

    /**
     * Gère les erreurs de connexion PDO.
     * @param PDOException $e
     */
    private static function handleError(PDOException $e): void {
        // Log de l'erreur (ou gestion personnalisée)
        error_log("Erreur de connexion : " . $e->getMessage());
        die("Une erreur est survenue lors de la connexion à la base de données.");
    }

    /**
     * Méthode pour loguer des informations (optionnel).
     * @param string $message
     */
    private static function log(string $message): void {
        // Exemple de log (adapter pour un logger si nécessaire)
        echo $message . "\n";
    }
}
