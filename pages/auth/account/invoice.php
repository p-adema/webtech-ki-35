<?php
require 'html_page.php';
auth_redirect(if_not_auth: '/auth/login');
html_header(title: 'Purchase invoice', styled: true, scripted: 'ajax');


html_footer();
