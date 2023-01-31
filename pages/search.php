<?php
require 'html_page.php';
html_header(title: 'Search results', styled: true, scripted: true);
$query = $_GET['tag'] ?? ''
?>

    <div class="content-wrapper">
        <div class="search-wrapper">
            <label for="search">
                <span class='search-icon material-symbols-outlined'> search </span>
            </label>
            <input class='search-input' type='text' placeholder='Search...' id='search' value="<?php echo $query ?>"/>
        </div>
        <div class="search-results" id="main-search-results"></div>
    </div>

<?php html_footer();
