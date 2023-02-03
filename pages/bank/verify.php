<?php
require 'html_page.php';
require 'bank_tags.php';
if (isset($_GET['tag']) and check_tag($_GET['tag'])):
html_header(title: 'Verify transaction', navbar: false, styled: true, scripted: true);

    require 'bank_functionality.php';


    $tag = $_GET['tag'];
    $user_info = obtain_user_information($tag);
    $user_id = $user_info['user_id']

    ?>
    <div class="background-box">
        <a href="/">
            <div class="title-box">
                <span class="title"> NietNG </span>
            </div>
        </a>
    </div>
    <div class="form-content">
        <h1 class='Header'>Verify payment</h1>
        <div class="form-outline">
            <span class="amount">€<?php echo $user_info['amount'] ?></span>
            <form method="post" data-tag="<?php echo $tag ?>">
                <div class="form-group">
                    <button type="submit" <?php
                    if (!enough_balance($user_id, $tag)) {
                        echo "class='long-btn form-submit disabled' disabled='true'";
                    } else {
                        echo "class='long-btn form-submit'";
                    }
                    ?>
                            value="Confirm">Confirm
                    </button>
                </div>
                <div class="form-group">
                    <button type="submit" class="long-btn form-submit"
                            value="Deny">Deny
                    </button>
                </div>
            </form>
        </div>
    </div>

<?php else:
    header('Location: /bank/');
    exit;
endif;

html_footer();
