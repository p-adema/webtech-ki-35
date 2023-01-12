<?php
function form_input(string $id, string $label, string $placeholder, string $type = 'text'): void
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
          />
        </div>
    ";
    echo $html;
}

function form_submit(string $text = 'Submit') {
    echo "<button type=\"submit\" class=\"btn btn-success\">
          $text
        </button>";
}
