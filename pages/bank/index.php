<!DOCTYPE html>
<html lang='en'>

<?php
require 'html_header.php';
html_header(title: 'balance', styled: true, scripted: false);
?>
<body>
<div id="main-container">
    <div id="big-balance-box">
        <div id="balance">
            Dit is je balans: €
            <?php
            require "bank.php";
            $user_id = 2;
            echo get_balance($user_id);
            ?>
        </div>
    </div>
</div>
</body>
</html>