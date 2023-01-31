<?php
require 'html_page.php';
html_header(title: 'Page not found', styled: 'form.css', scripted: false);
?>

    <div class="form-content">
        <h1> Invalid link </h1>
        <div class="form-outline">
            <form>
                <p> This page doesn't exist </p>
                <?php
                echo '<div class="form-btns">';
                text_link('Go back home', '/');
                echo '</div>';
                ?>
            </form>
        </div>
    </div>

<?php html_footer();
