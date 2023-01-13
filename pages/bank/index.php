<?php
require 'html_page.php';
html_header(title: 'balance', styled: true, scripted: false);
?>
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
<?php html_footer();
