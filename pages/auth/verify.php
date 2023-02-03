<?php
require 'tag_actions.php';

if (isset($_GET['tag'])) {
    $tag = $_GET['tag'];
    $valid = true;
    require 'api_resolve.php';

    $email_tag_check = email_tag_check($tag);
    if ($email_tag_check !== false) {
        if ($email_tag_check === 'verify') {
            ensure_session();
            $_SESSION['url_tag'] = $tag;
            $_SESSION['url_tag_type'] = 'verify';
            header('Location: /auth/account/verify');
            die();

        } elseif ($email_tag_check === 'password-reset') {
            ensure_session();
            $_SESSION['url_tag'] = $tag;
            $_SESSION['url_tag_type'] = 'password-reset';
            header('Location: /auth/reset_password');
            die();
        }
    }
}

require "html_page.php";
html_header('Invalid link', authentication: true);
echo "<p> This link doesn't seem quite right. </p>
<a href='/'> Go back to home </a>";
html_footer();
