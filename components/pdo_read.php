<?php
/**
 * Create a new read-only connection to the database
 * @param bool $err_fatal Whether to terminate script on error, or to reraise errors
 * @return PDO Database connection object
 */
function new_pdo_read(bool $err_fatal = true): PDO
{
    try {
        $tokens = $_SERVER['DOCUMENT_ROOT'] . '/../tokens/';
        $host = file_get_contents($tokens . 'hostname');
        $dsn = "mysql:dbname=db;host=$host;port=3306";
        $user = 'web-read';
        $password = file_get_contents($tokens . 'web-read');

        return new PDO($dsn, $user, $password);

    } catch (PDOException $e) {
        if (!$err_fatal) {
            throw $e;
        }
        echo "<br/>" . "SQL connection error (readonly): " . $e->getMessage() . "<br/>";
        die();
    }

}
