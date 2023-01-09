<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>DB Table</title>
</head>

<body>
<h1>DB example</h1>
<div>
    <table>
        <tr>
            <th>Id</th>
            <th>Message</th>
        </tr>
        <?php
            $user = 'root';
            $pass = '4S&qx6tbCH&HS5RT';
            $dsn = 'mysql:dbname=app;host=0.0.0.0;port=3306';

            try {
                $dbh = new PDO($dsn, $user, $pass);
                foreach ($dbh->query('SELECT * from message') as $row) {
        $html = "
        <tr>
            <td>{$row['id']}</td>
            <td>{$row['message']}</td>
        </tr>
        ";
        echo $html;
        }
        $dbh = null;
        } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
        }
        ?>
    </table>
</div>
</body>

</html>
