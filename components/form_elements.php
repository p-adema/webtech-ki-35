<?php
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
          <span class=\"form_error\"></span>
          <span></span>
        </div>
    ";
    echo $html;
}

function form_submit(string $text = 'Submit'): void
{
    $html = "
    <div id=\"submit-group\">
        <button type=\"submit\" class=\"form-submit\"> $text </button>
        <span class=\"form_error\"></span>
    </div>
    ";
    echo $html;
}
