function form_handle_errors(errors) {
    for (let form_elem in errors) {
        if (errors[form_elem].length !== 0) {
            $(`div#${form_elem}-group`).addClass("has-error")
            $(`span#${form_elem}-error`).css('visibility', 'visible').html(errors[form_elem].join('<br/>'));
        } else {
            $(`div#${form_elem}-group`).removeClass("has-error")
            $(`span#${form_elem}-error`).css('visibility', 'hidden').html('No error');
        }
    }
}
