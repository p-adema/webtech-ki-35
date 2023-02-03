<?php
require 'html_page.php';
auth_redirect(if_not_auth: '/checkout/auth');
html_header(title: 'Payment', styled: 'form.css', scripted: true);
?>
    <div class="form-content">
        <h1> Billing information </h1>
        <div class="form-outline">
            <form action="/api/account/billing" method="POST">
                <p> Please fill out your payment information to continue </p>
                <?php
                form_input('l_name', 'Legal Name');
                form_input('country', 'Country');
                form_input('city', 'City');
                form_input('zipcode', 'Zipcode');
                form_input('streetnum', 'Street number');
                form_error();

                echo '<div class="form-btns">';
                display_text_link('Back', '/checkout/review');
                form_submit(text: 'Confirm');
                echo '</div>';
                ?>
            </form>
        </div>
    </div>

<?php html_footer();
