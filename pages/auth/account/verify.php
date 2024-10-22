<?php
require 'html_page.php';
html_header(title: 'Verify account', authentication: true, styled: 'form.css', scripted: true);

?>
    <div class="form-content">
        <h1> Verify account </h1>
        <div class="form-outline">
            <form action="/api/verify" method="POST">
                <?php if (isset($_SESSION['url_tag']) and $_SESSION['url_tag_type'] === 'verify'):
                    form_submit('Activate account', extra_cls: 'long-btn');
                    form_error();
                else: ?>
                    <p> This link doesn't seem quite right. </p>
                    <a href="/"> Go back to home </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

<?php
html_footer();
