<?php
/**
 * Create a new read-write connection to the database
 * @return PDO Database connection object
 */
function new_pdo_write(): PDO
{
    $tokens = $_SERVER['DOCUMENT_ROOT'] . '/../tokens/';
    $host = file_get_contents($tokens . 'hostname');
    $dsn = "mysql:dbname=db;host=$host;port=3306";
    $user = 'web-write';
    $password = file_get_contents($tokens . 'web-write');

    $PDO = new PDO($dsn, $user, $password);
    return $PDO;

}
global $connection_write;
try {
    $connection_write = new_pdo_write();
} catch (PDOException) {
    echo 'Database is not available';
    exit;
}
function prepare_write($sql): PDOStatement
{
    global $connection_write;
    return $connection_write->prepare($sql);
}
