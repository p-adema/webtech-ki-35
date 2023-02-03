<?php
/**
 * Create a new read-only connection to the database
 * @return PDO Database connection object
 */
function new_pdo_read(): PDO
{
    $tokens = $_SERVER['DOCUMENT_ROOT'] . '/../tokens/';
    $host = file_get_contents($tokens . 'hostname');
    $dsn = "mysql:dbname=db;host=$host;port=3306";
    $user = 'web-read';
    $password = file_get_contents($tokens . 'web-read');

    return new PDO($dsn, $user, $password);


}

global $connection_read;

try {
    $connection_read = new_pdo_read();
} catch (PDOException) {
    echo 'Database is not available';
    exit;
}
function prepare_readonly($sql): PDOStatement
{
    global $connection_read;
    return $connection_read->prepare($sql);
}
