<?php
/**
 * Create a form input group with label and error span
 * @param string $id
 * @param string $label
 * @param string $placeholder
 * @param string $type
 * @param string $input_attrs
 * @return void Echoes to page
 */
function form_input(string $id, string $label, string $placeholder = '', string $type = 'text', string $input_attrs = ''): void
{
    $html = "
<div id='$id-group' class='form-group'>
    <label for='$id'>$label</label>
    <input
        type='$type'
        id='$id'
        name='$id'
        placeholder='$placeholder'
        $input_attrs
    />
    <span id='$id-error' class='form-error'> No error </span>
</div>
    ";
    echo $html;
}

function form_upload(string $id, string $label_text, string $button_text, string $types, string $icon): void
{
    $html = "
<div id='file-$id-group' class='form-group file-group'>
    <label for='file-$id'>$label_text</label>
    <label id='file-$id-button' class='file-button' for='file-$id'>
        <span class='file-button-icon material-symbols-outlined' data-icon='$icon'> upload </span>
        <span class='file-button-text'> $button_text </span>
    </label>
    <input
        type='file'
        id='file-$id'
        name='file-$id'
        accept='$types'
    />
    <span id='file-$id-error' class='form-error'> No error </span>
</div>
    ";
    echo $html;
}

function form_input_paragraph(string $id, string $label): void
{
    $html = "
<div id='$id-group' class='form-group'>
    <label for='$id'>$label</label>
        <textarea
            id='$id'
            name='$id'></textarea>
    <span id='$id-error' class='form-error'> No error </span>
</div>
    ";
    echo $html;
}

/**
 * Create a form submission button
 * @param string $text Text to be put in the button
 * @return void Echoes to page
 */
function form_submit(string $text = 'Submit', string $extra_cls = ''): void
{
    $html = "
<div id='submit-group' class='form-group submit-with-$extra_cls'>
    <button type='submit' class='form-submit $extra_cls'> $text </button>
</div>
    ";
    echo $html;
}

function form_dropdown(string $id, string $label, string $placeholder, array $options): void
{
    $options_rendered = [];
    foreach ($options as $option) {
        $options_rendered[] = "<option value='$option'> $option </option>";
    }
    $options_html = join(PHP_EOL, $options_rendered);

    $html = "
<div id='$id-group' class='form-group form-group-select'>
    <label for='$id'>$label</label>
    <select name='$id' id='$id'>
        <option value=''> $placeholder </option>
        $options_html
    </select>
    <span id='$id-error' class='form-error'> No error </span>
</div>
    ";
    echo $html;
}

function form_sortable_item(string $sortable_id, int $item_count, array $item, string $extra_class = '', string $anchor_icon = '', bool $has_icon = true, bool $draggable = true): string
{
    $name = strlen($item['name']) < 26 ? $item['name'] : substr($item['name'], 0, 25) . '...';
    $anchor = $anchor_icon ?: "<span class='input-sortable-index $extra_class'> $item_count </span>";
    $icon = $has_icon ? "<span class='input-sortable-item-icon $extra_class material-symbols-outlined'> drag_indicator </span>" : '';
    $drag = $draggable ? 'true' : 'false';
    return "
<div class='input-sortable-row $extra_class'>
    $anchor
    <div class='input-sortable-slot $extra_class' id='input-sortable-$sortable_id-$item_count'>
        <div class='input-sortable-item $extra_class' data-tag='{$item['tag']}' draggable='$drag'> 
            $icon
            <span class='input-sortable-item-name $extra_class'> $name </span>
        </div>
    </div>
</div>";
}

function form_sortable(string $id, string $label, array $items, bool $extendable = true, string $extendor_label = 'Add videos'): void
{
    $items_rendered = [];
    $count = 0;
    foreach ($items as $item) {
        $count++;
        $items_rendered[] = form_sortable_item($id, $count, $item);
    }
    $sortables_html = join(PHP_EOL, $items_rendered);
    $extendor_html = !$extendable ? '' : "
<div class='sortable-extra-wrapper sortable-extendor-wrapper' id='sortable-$id-extend-wrapper'> 
    <div class='sortable-extendor-button'>
        <span class='sortable-icon material-symbols-outlined'> add </span>
        <span class='sortable-extendor-label'> $extendor_label </span>
    </div>
</div>
<div class='sortable-extra-wrapper sortable-query-wrapper' id='sortable-$id-query-wrapper'> 
    <label class='sortable-query-button' for='sortable-$id-query'>
        <span class='sortable-icon sortable-query-icon material-symbols-outlined'> search </span>
        <input class='sortable-query' type='text' id='sortable-$id-query' placeholder='Search...' data-result-target='query-results-$id'/>
    </label>
</div>
<div class='sortable-query-results' id='query-results-$id'> </div>";

    $html = "
<div id='$id-group' class='form-group form-group-sort'>
    <label for='$id'>$label</label>
    <div name='$id' id='$id' class='input-sortable-wrapper'>
        $sortables_html
    </div>
    $extendor_html
    <span id='$id-error' class='form-error'> No error </span>
</div>
    ";
    echo $html;
}

/**
 * Create a form group with error span for a specific error type
 * @param string $err_id Type of error to display (commonly submission)
 * @return void Echoes to page
 */
function form_error(string $err_id = 'submit'): void
{
    echo "<div class='form-group'><span id = '$err_id-error' class='form-error'> No error </span></div>";
}

function form_upload_progress(): void
{
    echo '
<div class="form-group upload-progress-wrapper">
    <div id="upload-progress">
        <div class="upload-progress-bar"></div>
        <div class="upload-progress-text">0%</div>
    </div>
</div>';
}

function form_price(): void
{
    echo "
<div id='free-group' class='form-group'>
    <label for='type'>Video type</label>
    <fieldset id='type'>
        <div>
          <input type='radio' id='paid' name='type' value='paid' checked>
          <label for='paid'> Paid </label>
        </div>
        <div>
          <input type='radio' id='free' name='type' value='free'>
          <label for='free'> Free </label>
        </div>
    </fieldset>
    <span id='type-error' class='form-error'> No error </span>
</div>
<div id='price-group' class='form-group'>
    <label for='price'> Price </label>
    <input
        type='text'
        id='price'
        name='price'
    />
    <span id='price-error' class='form-error'> No error </span>
</div>
";
}
