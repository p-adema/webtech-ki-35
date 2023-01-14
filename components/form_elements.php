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
    <div id=\"$id-group\" class=\"form-group\">
          <label for=\"$id\">$label</label>
          <input
            type=\"$type\"
            class=\"form-control\"
            id=\"$id\"
            name=\"$id\"
            placeholder=\"$placeholder\"
            $input_attrs
          />
          <span class=\"form-error\"></span>
        </div>
    ";
    echo $html;
}

/**
 * Create a form submission button with error span
 * @param string $text Text to be put in the button
 * @return void Echoes to page
 */
function form_submit(string $text = 'Submit'): void
{
    $html = "
    <div id=\"submit-group\">
        <button type=\"submit\" class=\"form-submit\"> $text </button>
        <span class=\"form-error\"></span>
    </div>
    ";
    echo $html;
}
