<?php
/*
 * TODO: Taak 2
 * Users can:
 *      Change password
 *      Change email
 *      Change full name
 *      Delete account
 *
 * Eventually:
 *      View payments & invoices
 */
require 'html_page.php';
auth_redirect(if_not_auth: '/auth/login');
html_header(title: 'Account', styled: 'form.css', scripted: true);
require 'account_elements.php';
if ($_SESSION['auth']):
    $user_data = load_account_data($_SESSION['uid']);
?>
    <div class="form-content">
        <h1> Account </h1>
        <div class="form-outline">
            <form action="/api/account/index" method="POST">
                <?php
                form_input('name', 'Username', input_attrs: "value=\"$user_data[name]\"");
                form_input('email', 'E-mail', input_attrs: "value=\"$user_data[email]\"");
                form_input('full_name', 'Full name', input_attrs: "value=\"$user_data[full_name]\"");

                form_input('password', 'Current password', type: 'password', input_attrs: "autocomplete=\"new-password\"");
                form_input('new_password', 'New password', type: 'password', input_attrs: "autocomplete=\"new-password\"");
                form_input('repeated_password', 'Repeat password', type: 'password', input_attrs: "autocomplete=\"new-password\"");
                form_submit('Save changes', 'long-btn');
                ?>

            </form>
        </div>
    </div>

<?php else: ?>
<P> you are not logged in </P>

<?php endif;
html_footer();
