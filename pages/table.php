<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>DB Table</title>
    <link rel="stylesheet" href="global.css" type="text/css"/>
    <link rel="stylesheet" href="table.css" type="text/css"/>
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
        require_once "pdo_read.php";
        $pdo_read = new_pdo_read();

        foreach ($pdo_read->query('SELECT * from users') as $row) {
            $width = 3;
            $html = "
        <tr>
            <td style='--color: navy'>{$row['id']}</td>
            <td style='--color: red'>{$row['name']}</td>
            <td style='--color: navy'>{$row['email']}</td>
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
