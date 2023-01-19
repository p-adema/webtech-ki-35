<?php
require 'html_page.php';
html_header(title: 'Session test');

if (!isset($_SESSION['visits'])) {
    $_SESSION['visits'] = 0;
}
$_SESSION['visits'] += 1;

echo '<p> You have visited this site ', $_SESSION['visits'], ' times! <br/> </p>';


if ($_SESSION['auth']) {
    echo '<p> Your id is: "', $_SESSION['uid'], '" <br/></p>';
} else {
    echo '<p>You are not logged in <br/></p>';
}

text_link('Go back to home', '/');

html_footer();
