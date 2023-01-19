// Description load more function
$(document).ready(function () {
    $('button.collapsible').click(function (_) {
        $(this).toggleClass('active');
        if ($(this).hasClass('active')) {
            $('div.content').css('max-height', $('div.content').prop('scrollHeight'));
        } else {
            $('div.content').css('max-height', '0');
        }
    })
    $("form").submit(function (event) {
        event.preventDefault();
        $('button.form-submit').addClass('pressed').removeClass('error')

        let parameterList = new URLSearchParams(window.location.search)

        const user_data = {
            type: 'add',
            item: parameterList.get('tag')
        };

        const handler_options = {
            success_handler: form_custom_success
        }

        $.post("/api/cart/modify.php", user_data, form_default_response(handler_options));
    });

    function form_custom_success(_, __) {
        $('form#cart').hide()
    }

    const video_data = {
        type: 'item',
        on: (new URLSearchParams(window.location.search)).get('tag')
    }

    const handler_options = {
        error_handler: function (_, __) {
            $('div.comments').html("<span class='comments-error'> Comments couldn't be loaded </span>")
        },
        success_handler: function (data, __) {
            $('div.comments').html(data.html)
            $('button.show-replies').click(load_replies)
        }
    }

    $.post("/api/load/comments.php", video_data, form_default_response(handler_options));
})

function load_replies(_) {
    $(this).text("Hide replies")
    $(this).unbind('click').click(hide_replies)

    const tag = $(this).attr('query')

    const replies_data = {
        type: 'replies',
        on: tag
    }
    const handler_options = {
        error_handler: function (_, __) {
            $(`#replies-${tag}`).html("<span class='replies-error'> Replies couldn't be loaded </span>")
        },
        success_handler: function (data, __) {
            $(`#replies-${tag}`).html(data.html).children('button.show-replies').click(load_replies)
        }
    }

    $.post("/api/load/comments.php", replies_data, form_default_response(handler_options));

}

function hide_replies(_) {
    alert('Not implemented')
}
