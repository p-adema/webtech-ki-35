// Description load more function
$(document).ready(function () {
    $('div.description').click(function (_) {
        const $content = $(this).children('div.content');
        if ($(this).children('button.collapsible').toggleClass('active').hasClass('active')) {
            $content.css('max-height', $content.prop('scrollHeight'));
        } else {
            $content.css('max-height', '0');
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
    const tag = $(this).text(`Hide ${$(this).attr('count')}`).unbind('click').click(hide_replies).attr('query')

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
    const tag = $(this).text(`Show ${$(this).attr('count')}`).unbind('click').click(show_replies).attr('query')
    $(`#replies-${tag}`).hide()
}

function show_replies(_) {
    const tag = $(this).text(`Hide ${$(this).attr('count')}`).unbind('click').click(hide_replies).attr('query')
    $(`#replies-${tag}`).show()
}
