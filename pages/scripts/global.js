function form_handle_respone(options) {
    return function (response_raw) {
        try {
            const response = JSON.parse(response_raw);
            console.log(response);

            if (!response.success) {
                form_handle_errors(response.errors);
            } else {
                form_handle_success(response.message, options)
            }
        } catch (e) {
            console.log(response_raw);
            console.log(e);
            $('button.form-submit').addClass('error')
        } finally {
            $('button.form-submit').removeClass('pressed')
        }
    }
}

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

function form_handle_success(message, options) {
    if ('redirect' in options) {
        $("form").html(
            '<div class="form-success clickable"> ' +
            '       <span>' + message + "</span> " +
            "       <span> <br /> You will be redirected shortly <br /> (or: click here) </span>" +
            "     </div>"
        )

        function redirect() {
            $(location).attr('href', options.redirect.link)
        }

        $("div.form-success").click(redirect)
        setTimeout(redirect, options.redirect.delay)
    } else {
        $("form").html(
            '<div class="form-success"> ' +
            '       <span>' + message + "</span> " +
            "     </div>"
        )
    }
}
