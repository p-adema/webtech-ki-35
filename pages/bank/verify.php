<?php
require 'html_page.php';
html_header(title: 'Verify transaction', styled: true, scripted: false);

# /bank/verify.php?tag=ferghejugheiur
# pay button -> api for moving payment from pending to log and change balance
# cancel button -> api removes pending payment

require 'bank_functionality.php';
require 'bank_tags.php';

if (isset($_GET['tag']) and check_tag($_GET['tag'])):

    $tag = $_GET['tag'];
    $user_id = obtain_user_information($tag);

    ?>
        <span class='Header'>Verify payment</span>
        <div class='main-container'>
            <div class='confirm-box'>
                <?php if (isset($_POST['button1'])) {
                    confirm_payment($user_id);
                } ?>
            </div>
            <div class='deny-box'>
                <?php if (isset($_POST['button2'])) {
                    deny_payment($tag);
                } ?>
            </div>
        </div>

        <form method="post">
          <input type="submit" name="button1"
            value="Confirm"/>
          <input type="submit" name="button2"
            value="Deny"/>
        </form>

<?php endif; ?>

<?php else: ?> <span>This link doesn't seem quite right.</span>
<?php endif;

html_footer();