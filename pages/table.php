<?php
require 'html_page.php';
html_header('DB Example');
?>

<h1>Users</h1>
<div>
    <table>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
        </tr>

        <?php
        require 'pdo_read.php';
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

<?php
require_once "link.php";
text_link('Go back to home', '/');

html_footer();
