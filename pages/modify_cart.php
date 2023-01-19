<?php
require 'html_page.php';
html_header(title: 'Cart modify', styled: 'form.css', scripted: true);
?>

    <div class="form-content">
        <h1> Modify example_paid cart status </h1>
        <div class="form-outline">
            <form id="add">
                <?php
                form_submit(text: 'Add to cart', extra_cls: 'long-btn');
                form_error('item');
                form_error();
                ?>
            </form>
            <form id="remove">
                <?php
                form_submit(text: 'Remove from cart', extra_cls: 'long-btn');
                form_error('item');
                form_error();
                text_link('Go back to home', '/');
                ?>
            </form>
        </div>
    </div>

<?php html_footer();
