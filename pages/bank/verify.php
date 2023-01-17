<?php
require 'html_page.php';
html_header(title: 'Verify transaction', styled: true, scripted: false);

# /bank/verify.php?tag=ferghejugheiur
# pay button -> api for moving payment from pending to log and change balance
# cancel button -> api removes pending payment

require 'verify.php';

"<span class='Header'>Verify payment</span>";
"<div class='main-container'>";
    "<div class='confirm-box'>";
    echo obtain_user_information('Hallo');
    "</div>";
    "<div class='confirm'>";
    "</div>";
"</div>";

html_footer();