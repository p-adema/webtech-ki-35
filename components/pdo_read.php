<?php
function new_pdo_read(): PDO
{
    try {
        $host = file_get_contents('../tokens/hostname');
        $dsn = "mysql:dbname=app;host=$host;port=3306";
        $user = 'web-read';
        $password = file_get_contents('../tokens/web-read');

        return new PDO($dsn, $user, $password);

    } catch (PDOException $e) {
        echo "<br/>" . "SQL connection error (readonly): " . $e->getMessage() . "<br/>";
        die();
    }

}
