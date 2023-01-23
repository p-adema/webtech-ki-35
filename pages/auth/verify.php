<?php
require 'tag_actions.php';

$tag = $_GET['tag'];
$valid = true;
if (isset($tag)) {
    require 'api_resolve.php';

    # TODO: make more efficient (one query to get tag)

    if (tag_check($tag, 'verify')) {
        ensure_session();
        $_SESSION['url_tag'] = $tag;
        $_SESSION['url_tag_type'] = 'verify';
        header('Location: /auth/account/verify');
        die();

    } elseif (tag_check($tag, 'password-reset')) {
        ensure_session();
        $_SESSION['url_tag'] = $tag;
        $_SESSION['url_tag_type'] = 'password-reset';
        header('Location: /auth/reset_password');
        die();
    }
}

require "html_page.php";
html_header('Invalid link');
echo "<p> This link doesn't seem quite right. </p>
<a href='/'> Go back to home </a>";
html_footer();
