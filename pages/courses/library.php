<?php
require 'html_page.php';

require_once 'owned_items.php';
auth_redirect(if_not_auth: '/auth/login');
html_header(title: 'Owned videos', styled: true, scripted: true);


$videos = get_owned_videos('');


?>
    <div class="content-wrapper">
        <div class="header-wrapper">
            <h1> Owned videos </h1>
        </div>
        <div class="search-wrapper">
            <label for="search">
                <span class='search-icon material-symbols-outlined'> search </span>
            </label>
            <input class='search-input' type='text' placeholder='Search...' id='search'>
        </div>
        <div class='videos-wrapper'>
            <?php
            if (empty($videos)) {
                display_no_videos();
            } else {
                echo render_owned_videos($videos);
            } ?>
        </div>
    </div>
<?php

html_footer();
