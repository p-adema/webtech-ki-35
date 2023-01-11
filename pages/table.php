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
        require_once "../components/pdo_read.php";
        $pdo_read = new_pdo_read();

        foreach ($pdo_read->query('SELECT * from message') as $row) {
            $html = "
        <tr>
            <td>{$row['id']}</td>
            <td>{$row['message']}</td>
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
