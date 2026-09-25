<?php
/**
 * Classe Database
 * Gère la connexion et la déconnexion à la base de données MySQL.
 * Utilise le pattern Singleton pour garantir une seule instance de connexion.
 */
class Database {
    // Configuration de la base de données
    private static string $dbHost = "localhost";
    private static string $dbName = "burgercode_fini";
    private static string $dbUsername = "root";
    private static string $dbUserpassword = "";
    
    // Instance unique de la connexion PDO
    private static ?PDO $connection = null;

    /**
     * Constructeur privé pour empêcher l'instanciation directe.
     */
    private function __construct() {}

    /**
     * Empêche le clonage de l'instance.
     */
    private function __clone() {}

    /**
     * Connecte à la base de données et retourne l'instance PDO.
     * Utilise le pattern Singleton pour garantir une seule connexion.
     *
     * @return PDO Instance de la connexion PDO
     * @throws PDOException Si la connexion échoue
     */
    public static function connect(): PDO {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO(
                    "mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName . ";charset=utf8mb4",
                    self::$dbUsername,
                    self::$dbUserpassword,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::ATTR_PERSISTENT => false, // Désactive les connexions persistantes
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                    ]
                );
            } catch (PDOException $e) {
                // Log l'erreur pour le débogage (à adapter selon ton environnement)
                error_log("Erreur de connexion à la base de données : " . $e->getMessage());
                throw new PDOException("Impossible de se connecter à la base de données. Veuillez réessayer plus tard.");
            }
        }
        return self::$connection;
    }

    /**
     * Déconnecte de la base de données en fermant la connexion.
     */
    public static function disconnect(): void {
        self::$connection = null;
    }

    /**
     * Vérifie si une connexion est active.
     *
     * @return bool True si une connexion est active, false sinon
     */
    public static function isConnected(): bool {
        return self::$connection !== null;
    }

    /**
     * Exécute une requête préparée avec des paramètres.
     *
     * @param string $sql Requête SQL à exécuter
     * @param array $params Paramètres à lier à la requête
     * @return PDOStatement Résultat de la requête
     * @throws PDOException Si l'exécution échoue
     */
    public static function executeQuery(string $sql, array $params = []): PDOStatement {
        $stmt = self::connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Récupère une seule ligne de résultat.
     *
     * @param string $sql Requête SQL à exécuter
     * @param array $params Paramètres à lier à la requête
     * @return array|null Tableau associatif représentant la ligne, ou null si aucun résultat
     */
    public static function fetchOne(string $sql, array $params = []): ?array {
        $stmt = self::executeQuery($sql, $params);
        return $stmt->fetch() ?: null;
    }

    /**
     * Récupère toutes les lignes de résultat.
     *
     * @param string $sql Requête SQL à exécuter
     * @param array $params Paramètres à lier à la requête
     * @return array Tableau de tableaux associatifs représentant les lignes
     */
    public static function fetchAll(string $sql, array $params = []): array {
        $stmt = self::executeQuery($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Récupère une seule valeur (ex: COUNT, MAX, etc.).
     *
     * @param string $sql Requête SQL à exécuter
     * @param array $params Paramètres à lier à la requête
     * @return mixed Valeur récupérée, ou null si aucun résultat
     */
    public static function fetchValue(string $sql, array $params = []) {
        $stmt = self::executeQuery($sql, $params);
        return $stmt->fetchColumn();
    }

    /**
     * Débute une transaction.
     */
    public static function beginTransaction(): void {
        self::connect()->beginTransaction();
    }

    /**
     * Valide une transaction.
     */
    public static function commit(): void {
        self::connect()->commit();
    }

    /**
     * Annule une transaction.
     */
    public static function rollBack(): void {
        self::connect()->rollBack();
    }
}
?>