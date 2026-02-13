<?php
/**
 * Database Configuration for Quran Database Web Application
 * 
 * This file contains the database connection settings.
 * For SQLite, we use the quran.sqlite file in the root directory.
 */

// Database configuration
define('DB_TYPE', 'sqlite'); // Options: 'sqlite', 'mysql', 'postgresql'

// SQLite configuration (default)
define('DB_PATH', dirname(__DIR__, 2) . '/quran.sqlite');

// MySQL configuration (if needed)
define('DB_HOST', 'localhost');
define('DB_NAME', 'quran_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// PostgreSQL configuration (if needed)
define('PG_HOST', 'localhost');
define('PG_PORT', '5432');
define('PG_NAME', 'quran_db');
define('PG_USER', 'postgres');
define('PG_PASS', '');

/**
 * Get database connection based on configuration
 * 
 * @return PDO Database connection
 * @throws PDOException If connection fails
 */
function getDatabase() {
    try {
        switch (DB_TYPE) {
            case 'sqlite':
                $pdo = new PDO('sqlite:' . DB_PATH);
                break;
                
            case 'mysql':
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                $pdo = new PDO($dsn, DB_USER, DB_PASS);
                break;
                
            case 'postgresql':
                $dsn = "pgsql:host=" . PG_HOST . ";port=" . PG_PORT . ";dbname=" . PG_NAME;
                $pdo = new PDO($dsn, PG_USER, PG_PASS);
                break;
                
            default:
                throw new Exception("Invalid database type: " . DB_TYPE);
        }
        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Get all chapters from the database
 * 
 * @param PDO $db Database connection
 * @return array List of chapters
 */
function getAllChapters($db) {
    $stmt = $db->query("SELECT id, name_ar, name_pron_en, class, verses_number FROM chapters ORDER BY id");
    return $stmt->fetchAll();
}

/**
 * Get a single chapter by ID
 * 
 * @param PDO $db Database connection
 * @param int $id Chapter ID
 * @return array|false Chapter data or false if not found
 */
function getChapter($db, $id) {
    $stmt = $db->prepare("SELECT * FROM chapters WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Search chapters by name
 * 
 * @param PDO $db Database connection
 * @param string $query Search query
 * @return array List of matching chapters
 */
function searchChapters($db, $query) {
    $stmt = $db->prepare("SELECT id, name_ar, name_pron_en, class, verses_number 
                          FROM chapters 
                          WHERE name_ar LIKE ? OR name_pron_en LIKE ? 
                          ORDER BY id");
    $searchTerm = "%{$query}%";
    $stmt->execute([$searchTerm, $searchTerm]);
    return $stmt->fetchAll();
}
