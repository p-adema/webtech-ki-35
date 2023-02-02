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
        <div class="controls-wrapper">
            <div class="controls-wrapper-inner">
                <span class="filters-label"> Filter results: </span>
                <div class="control-buttons-wrapper" id="filter-buttons">
                    <span class="control-button filter-all active"
                          data-class="search-results"> All </span>

                    <span class="control-button filter-available"
                          data-class="search-results hide-unowned"> Available </span>

                    <span class="control-button filter-owned"
                          data-class="search-results hide-unowned hide-free"> Owned </span>
                </div>
            </div>
            <span class="controls-gap"></span>
            <div class="controls-wrapper-inner">
                <span class="sort-label"> Sort by: </span>
                <div class="control-buttons-wrapper" id="sort-buttons">
                    <span class="control-button sort-views"
                          data-sort="views"> Views </span>

                    <span class="control-button sort-rating"
                          data-sort="rating"> Rating </span>

                    <span class="control-button sort-recent"
                          data-sort="recent"> Recent </span>
                </div>
            </div>
        </div>
        <div class="search-results" id="main-search-results"></div>
    </div>

<?php html_footer();
