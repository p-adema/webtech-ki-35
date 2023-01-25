// Description load more function
$(document).ready(function () {
    const video_tag = $('.comments').attr('tag')
    if ($('#sidebar-load-success').attr('tag') === '1') {
        $('.big-video-block').scrollTop($('#current-video-playing').offset().top - $('#video_scroll_0').offset().top);
    }
    $('div.description').click(function (_) {
        const $content = $(this).children('div.content');
        if ($(this).children('button.collapsible').toggleClass('active').hasClass('active')) {
            $content.css('max-height', $content.prop('scrollHeight'));
        } else {
            $content.css('max-height', '0');
        }
    })
    $(".shop").submit(function (event) {
        event.preventDefault();

        if (this.id === 'add') {

            $('button.form-submit').addClass('pressed').removeClass('error')

            const user_data = {
                type: 'add',
                item: video_tag
            };

            const handler_options = {
                success_handler: form_custom_success
            }

            $.post("/api/cart/modify", user_data, form_default_response(handler_options));
        } else {

            $('button.form-submit').addClass('pressed').removeClass('error')

            window.location.href = "/show_cart"

        }
    })

    $(".comment-submit").submit(function (event) {
        event.preventDefault()

        const parameter_list = new URLSearchParams(window.location.search)

        const user_data = {
            video_tag: parameter_list.get('tag'),
            message: $("#message").val()
        }
        const handler_options = {
            success_handler: function(data, _) {
            $(`.top`).html(data.html);
        }}

        $.post('/api/courses/video.php', user_data, form_default_response(handler_options))
    })

    const stars = $(".stars");

    stars.mousemove(function (event) {
            let x_move = event.pageX - event.currentTarget.offsetLeft;
            let tot_length = $(this).width();

            let star_count = Math.ceil(((x_move / tot_length) * 5))

            stars.removeClass(['star-1', 'star-2', 'star-3', 'star-4', 'star-5']).addClass([`star-${star_count}`]);
        }
    )

    stars.mouseleave(function (_) {
        stars.removeClass(['star-5', 'star-4', 'star-3', 'star-2', 'star-1'])
    });

    stars.click(function (event) {
        let x_cord = event.pageX - event.currentTarget.offsetLeft;
        let tot_length = $(this).width();

        let star_count = Math.ceil(((x_cord / tot_length) * 5))

        const video_data = {
            type: 'item',
            on: video_tag
        }
        stars.removeClass().addClass(['stars', `perm-star-${star_count}`])
        jQuery.post('/api/courses/stars', {star: star_count, tag: video_data})
    })

    function form_custom_success(_, __) {
        $('form#add').hide()
        $('form#cart').show()
    }

    const video_data = {
        type: 'item',
        on: video_tag
    }

    const handler_options = {
        error_handler: function (_, __) {
            $('div.comments').html("<span class='comments-error'> Comments couldn't be loaded </span>")
        },
        success_handler: function (data, __) {
            $('div.comments').html(data.html)
            $('button.show-replies').click(load_replies)
            bind_score()
            document.getElementById("message").value = "";
            open_reply()
        }
    }

    $.post("/api/load/comments", video_data, form_default_response(handler_options));
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
            $(`#replies-${tag}`).html(data.html);
            $(`#replies-${tag} button.show-replies`).click(load_replies);
            bind_score()
            open_reply()
        }
    }

    $.post("/api/load/comments", replies_data, form_default_response(handler_options));

}

function hide_replies(_) {
    const tag = $(this).text(`Show ${$(this).attr('count')}`).unbind('click').click(show_replies).attr('query')
    $(`#replies-${tag}`).hide()
}

function show_replies(_) {
    const tag = $(this).text(`Hide ${$(this).attr('count')}`).unbind('click').click(hide_replies).attr('query')
    $(`#replies-${tag}`).show()
}

function bind_score() {
    $('.comment-reactions-up').click(function (event) {
        event.preventDefault();
        let comment_id = $(this).parent().attr('tag')
        $.post("/api/courses/comments.php", {rating: 1, comment: comment_id})
        $(this).addClass('pressed')
        $(`#${comment_id}`).find('.comment-reactions-down').removeClass('pressed')
    })
    $('.comment-reactions-down').click(function (event) {
        event.preventDefault()
        let comment_id = $(this).parent().attr('tag')
        $.post("/api/courses/comments.php", {rating: -1, comment: comment_id})
        $(this).addClass('pressed')
        $(`#${comment_id}`).find('.comment-reactions-up').removeClass('pressed')
    })
}

function open_reply() {
    $('.comment-reactions-reply-box').click(function (event) {
        event.preventDefault()
        let $reply = $(this).children('.reply-box');
        if ($(this).toggleClass('active').hasClass('active')) {
            $reply.css('max-height', $reply.prop('scrollHeight'));
        } else {
            $reply.css('max-height', '0');
        }
    })
}