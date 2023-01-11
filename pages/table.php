<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>DB Table</title>
</head>

<body>
<h1>Users</h1>
<div>
    <table>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
        </tr>

        <?php
        require_once "../components/pdo_read.php";
        $pdo_read = new_pdo_read();

        foreach ($pdo_read->query('SELECT * from users') as $row) {
            $html = "
        <tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
        </tr>
        ";
            echo $html;
        }
        $pdo_read = null;
        ?>

    </table>
</div>
</body>

</html>
