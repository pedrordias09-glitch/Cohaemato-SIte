<?php
require_once __DIR__ . '/config.php';

/**
 * Devolve uma ligação PDO, ou null se a BD estiver desligada / indisponível.
 * O site continua a funcionar com dados de exemplo quando devolve null.
 */
function db(): ?PDO
{
    static $pdo = false;
    if ($pdo !== false) {
        return $pdo;
    }
    if (!DB_ENABLED) {
        return $pdo = null;
    }
    try {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        error_log('Cohaemato DB: ' . $e->getMessage());
        $pdo = null;
    }
    return $pdo;
}
