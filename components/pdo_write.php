<?php
function new_pdo_write(): PDO
{
    try {
        $host = file_get_contents('../tokens/hostname');
        $dsn = "mysql:dbname=db;host=$host;port=3306";
        $user = 'web-write';
        $password = file_get_contents('../tokens/web-write');

        return new PDO($dsn, $user, $password);

    } catch (PDOException $e) {
        echo "<br/>" . "SQL connection error (read/write): " . $e->getMessage() . "<br/>";
        die();
    }

}
