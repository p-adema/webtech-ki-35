function form_default_response(options) {
    options.form = options.hasOwnProperty('multi_form') ? 'form#' + options.multi_form.target : 'form';

    return function (response_raw) {
        try {
            const response = JSON.parse(response_raw);
            console.log(response);

            if (!response.success) {
                (options.hasOwnProperty('error_handler') ? options.error_handler : form_default_errors)(response.errors, options)
            } else {
                (options.hasOwnProperty('success_handler') ? options.success_handler : form_default_success)(response.data, options)
            }
        } catch (e) {
            console.log(response_raw);
            console.log(e);
            $(`${options.form} button.form-submit`).addClass('error')
        } finally {
            $(`${options.form} button.form-submit`).removeClass('pressed')
        }
    }
}

function form_default_errors(errors, options) {
    for (let form_elem in errors) {
        if (errors[form_elem].length !== 0) {
            $(`${options.form} div#${form_elem}-group`).addClass("has-error")
            $(`${options.form} span#${form_elem}-error`).css('visibility', 'visible').html(errors[form_elem].join('<br/>'));
        } else {
            $(`${options.form} div#${form_elem}-group`).removeClass("has-error")
            $(`${options.form} span#${form_elem}-error`).css('visibility', 'hidden').html('No error');
        }
    }
}

function form_default_success(data, options) {
    if ('redirect' in options) {
        if ('multi_form' in options && 'clear_other' in options.multi_form && options.multi_form.clear_other) {
            $('form').html('');
        }
        $(options.form).html(
            '<div class="form-success clickable"> ' +
            '       <span>' + data.message + "</span> " +
            "       <span> <br /> You will be redirected shortly <br /> (or: click here) </span>" +
            "     </div>"
        )


        function redirect() {
            $(location).attr('href', options.redirect.link)
        }

        $("div.form-success").click(redirect)
        setTimeout(redirect, options.redirect.hasOwnProperty('delay') ? options.redirect.delay : 0)
    } else {
        $(options.form).html(
            '<div class="form-success"> ' +
            '       <span>' + data.message + "</span> " +
            "     </div>"
        )
    }
}

function close_sidebar() {
    // $(#sidebar).css('width', '250px');
    document.getElementById("sidebar").style.width = "250px";
    document.getElementById('sidebar-button').style.width = "250px";
}

function open_sidebar() {
    document.getElementById("sidebar").style.width = "0";
    document.getElementById('sidebar-button').style.width = "0";
}




function openRightMenu() {
    $('.sidebar_right').animate({right: '-0'}, 400);
    $sidebar_open = true
}

function closeRightMenu() {
    $('.sidebar_right').animate({right: '-300px'}, 400);
    $sidebar_open = false
}

function go_to_checkout() {
    $(location).attr('href', '/show_cart.php')
}
