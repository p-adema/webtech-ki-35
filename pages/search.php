<?php
require 'html_page.php';
html_header(title: 'Search results', styled: true, scripted: true);
$query = $_GET['tag'] ?? '';
$owned_empty_text = $_SESSION['auth'] ? "
<span> You don't have any videos! </span> <span> Explore to find some. </span>
" : "
<span> You aren't logged in! </span> <span> Authenticate first </span>
";
$owned_empty_link = $_SESSION['auth'] ? '/courses/browse_videos' : '/auth/login';
?>

    <div class="content-wrapper">
        <div class="search-wrapper">
            <label for="search">
                <span class='search-icon material-symbols-outlined'> search </span>
            </label>
            <input class='search-input' type='text' placeholder='Search...' id='search' value="<?php echo $query ?>">
        </div>
        <div class="controls-wrapper">
            <div class="controls-wrapper-inner">
                <span class="controls-label"> Filter results: </span>
                <div class="control-buttons-wrapper" id="filter-buttons">
                    <span class="control-button filter-all active"
                          data-class="search-results" data-filter="all"> All </span>

                    <span class="control-button filter-available"
                          data-class="search-results hide-unowned" data-filter="available"> Available </span>

                    <span class="control-button filter-owned"
                          data-class="search-results hide-unowned hide-free" data-filter="owned"> Owned </span>
                </div>
            </div>
            <span class="controls-gap"></span>
            <div class="controls-wrapper-inner">
                <span class="controls-label"> Sort by: </span>
                <div class="control-buttons-wrapper" id="sort-buttons">
                    <span class="control-button sort-views active"
                          data-sort="views"> Views </span>

                    <span class="control-button sort-rating"
                          data-sort="rating"> Rating </span>

                    <span class="control-button sort-recent"
                          data-sort="recent"> Recent </span>
                </div>
            </div>
        </div>
        <div class="search-results" id="main-search-results"></div>
        <a class="result-empty" id="owned-empty"
           href="<?php echo $owned_empty_link; ?>"> <?php echo $owned_empty_text; ?> </a>
        <span class="result-empty"
              id="available-empty"> <span> There aren't any videos available to you on this topic </span> <span
                    id="available-empty-slot"></span> </span>
        <a href="/upload/" class="result-empty" id="all-empty"> <span> There aren't any videos on this topic </span>
            <span> You can upload some though! </span> </a>
    </div>

<?php html_footer();
