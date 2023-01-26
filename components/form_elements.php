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
        <span class='file-button-icon material-symbols-outlined' data_icon='$icon'> upload </span>
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
