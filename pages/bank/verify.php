<?php
require 'html_page.php';
html_header(title: 'Verify transaction', styled: true, scripted: true);

require 'bank_functionality.php';
require 'bank_tags.php';

if (isset($_GET['tag']) and check_tag($_GET['tag'])):

    $tag = $_GET['tag'];
    $user_id = obtain_user_information($tag);

    ?>

        <span class='Header'>Verify payment</span>
<!--        <div class='main-container'>-->
<!--            <div class='confirm-box'>-->
<!--                --><?php //if (isset($_POST['button1'])) {
//                    confirm_payment($user_id);
//                } ?>
<!--            </div>-->
<!--            <div class='deny-box'>-->
<!--                --><?php //if (isset($_POST['deny'])) {
//                    deny_payment($tag);
//                } ?>
<!--            </div>-->
<!--        </div>-->

        <form method="post">
          <input type="submit" <?php
                if (!enough_balance($user_id, $tag)) {
                    echo "class='disabled' disabled='true'";
                } ?>
            value="Confirm"/>
          <input type="submit"
            value="Deny"/>
        </form>

<?php else: ?> <span>This link doesn't seem quite right.</span>
<?php endif;

html_footer();