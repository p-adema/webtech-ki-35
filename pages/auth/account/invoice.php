<?php
require 'html_page.php';
auth_redirect(if_not_auth: '/auth/login.php');
html_header(title: 'Purchase invoice', styled: true, scripted: false);


html_footer();
