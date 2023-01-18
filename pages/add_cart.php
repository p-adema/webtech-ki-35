<?php
require 'html_page.php';
html_header(title: 'Add Bunny to cart', styled: 'form.css', scripted: true);
?>

    <div class="form-content">
        <h1> Add Bunny to cart </h1>
        <div class="form-outline">
            <form action="/api/cart/add.php" method="POST">
                <?php
                require "form_elements.php";
                require "link.php";

                form_submit(text: 'Add to cart', extra_cls: 'long-btn');
                form_error('item');
                form_error();
                text_link('Go back to home', '/');
                ?>
            </form>
        </div>
    </div>

<?php html_footer();
