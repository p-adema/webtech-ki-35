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

$.upload = function (target, user_data, success_handler) {
    $('#upload-progress').css('visibility', 'visible')
    $.ajax({
        type: "POST",
        url: target,
        xhr: function () {
            let xhr_settings = $.ajaxSettings.xhr();
            xhr_settings.upload.addEventListener('progress', upload_progress_handler);
            return xhr_settings;
        },
        success: success_handler,
        error: function (error) {
            console.log('Ajax upload error')
            console.log(error)
        },
        data: user_data,
        contentType: false,
        processData: false,
    })
}

function openRightMenu() {
    $('.sidebar_right').animate({right: '-0'}, 400);
}

function closeRightMenu() {
    $('.sidebar_right').animate({right: '-300px'}, 400);
}


function open_right_menu() {
    $('.sidebar-right').animate({right: '-0'}, 400);
    $('.sidebar-active-cover').toggleClass('hidden').animate({opacity: 0.5}, 400)
}

function go_to_checkout() {
    $(location).attr('href', '/checkout/review')
}

function close_right_menu() {
    $('.sidebar-right').animate({right: '-300px'}, 400);
    $('.sidebar-active-cover').toggleClass('hidden').animate({opacity: 0}, 400)

}

function redirect(link = '/show_cart') {
    $(location).attr('href', link)
}

function symbol_default_enter(_) {
    $(this).css('font-variation-settings', "'wght' 600")
}

function symbol_default_leave(_) {
    $(this).css('font-variation-settings', "'wght' 400")
}

$(document).ready(function () {
    let $dropdown_videos = $('.dropdown-videos');
    let $dropdown_videos_content = $('.dropdown-videos-content')
    $dropdown_videos_content.stop().animate({opacity: 0, left: $dropdown_videos.offset().left, top: -$dropdown_videos_content.height() }, 40)
    $('.sidebar-active-cover').click(function (_) {
        close_right_menu()
    })
    let $dropdown = $('.dropdown');
    $dropdown.mouseenter(function (_) {
        $('.dropdown-content').stop().show().animate({opacity: 1, right: 0}, 400)
    })
    $dropdown.mouseleave(function (_) {
        $('.dropdown-content').stop().animate({opacity: 0, right: -300}, 400)
    })

    $('.cart-item-delete').click(cart_item_delete)

    $('.link-back').click(function (event) {
        event.preventDefault();
        window.history.back();
    })
    $dropdown_videos.mouseenter(function (_) {
        $dropdown_videos_content.stop().animate({left: $dropdown_videos.offset().left }, 4)

        $('.dropdown-videos-content').show().animate({opacity: 1, top: 60}, 400)
        $dropdown_videos.css('background-color', '#676')
    })
    $dropdown_videos.mouseleave(function (_) {
        $dropdown_videos.css('background-color', '#343')
        $dropdown_videos_content.stop().animate({opacity: 0, top: -$dropdown_videos_content.height()}, 400)

    })
})

function cart_item_delete(event) {
    event.preventDefault();

    const user_data = {
        type: 'remove',
        item: $(this).attr('tag')
    };

    const handler_options = {
        error_handler: remove_item_err,
        success_handler: remove_item_success
    };

    $.post("/api/cart/modify", user_data, form_default_response(handler_options));
}

function remove_item_err(errors, _) {
    console.log(errors)
}

function remove_item_success(data, __) {
    $(`a[tag=${data.tag}]`).fadeOut('fast', function () {
        $(this).remove()

    })
}
