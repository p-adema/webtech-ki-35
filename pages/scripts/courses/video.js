// Description load more function
$(document).ready(function () {
    const video_tag = $('.comments').attr('tag')
    if ($('#sidebar-load-success').attr('tag') === '1') {
        $('.big-video-block').scrollTop($('#current-video-playing').offset().top - $('#video_scroll_0').offset().top);
    }
    $('div.description').click(function (_) {
        const $content = $(this).children('div.content');
        if ($(this).children('button.collapsible').toggleClass('active').hasClass('active')) {
            $content.css('max-height', $content.prop('scrollHeight')).css('padding-bottom', '15px');
        } else {
            $content.css('max-height', '0').css('padding-bottom', '5px');
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

            $.post('/api/cart/modify', user_data, form_default_response(handler_options));
        } else {

            $('button.form-submit').addClass('pressed').removeClass('error')

            window.location.href = "/checkout/review"

        }
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

        stars.removeClass().addClass(['stars', `perm-star-${star_count}`])

        const user_data = {
            star: star_count,
            tag: $('#video').attr('data-tag')
        };

        $.post('/api/courses/rate_item', user_data);
    })

    function form_custom_success(data, __) {
        $('form#add').hide();
        $('form#cart').show();
        $('.cart-item-anchor').remove();
        $('.sidebar-close').after(data.html);
        $('.cart-item-delete').click(cart_item_delete);

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
        }
    }

    $.post('/api/load/comments', video_data, form_default_response(handler_options));
}).on('click', '.comment-reactions-up', function (event) {
    event.preventDefault();
    const tag = $(this).parent().attr('data-tag')
    $.post('/api/courses/score_comment', {rating: 1, comment: tag})
    $(this).addClass('pressed')
    $(`#${tag}`).find('.comment-reactions-down').removeClass('pressed')
}).on('click', '.comment-reactions-down', function (event) {
    event.preventDefault()
    const tag = $(this).parent().attr('data-tag')
    $.post('/api/courses/score_comment', {rating: -1, comment: tag})
    $(this).addClass('pressed')
    $(`#${tag}`).find('.comment-reactions-up').removeClass('pressed')
}).on('click', '.comment-reactions-reply-box', function (event) {
    event.preventDefault()
    const tag = $(this).parent().attr('data-tag');
    const $reply = $(`#new-reply-${tag}`);
    if ($(this).toggleClass('active').hasClass('active')) {
        $reply.css('max-height', $reply.prop('scrollHeight'));
        setTimeout(function () {
            $reply.css('max-height', '');
        }, 200)
    } else {
        $reply.css('max-height', $reply.prop('scrollHeight'));
        setTimeout(function () {
            $reply.css('max-height', '0');
        })
    }
}).on('submit', '.new-comment', function (event) {
    event.preventDefault()
    if ($(this).attr('data-auth') === 'no') {
        window.location.href = '/auth/login';
        return
    }
    const $comment = $(this).parent().parent();
    let user_data;
    if ($(this).attr('data-reply') === 'yes') {
        user_data = {
            item_tag: $('#video').attr('data-tag'),
            comment_tag: $(this).attr('data-tag'),
            message: $(this).find('textarea').val()
        }
    } else {
        user_data = {
            item_tag: $(this).attr('data-tag'),
            message: $(this).find('textarea').val()
        }
    }
    const handler_options = {
        success_handler: function (data, _) {
            $('.comments').prepend(data.html);
            $comment.remove();
        }
    }

    $.post('/api/courses/add_comment', user_data, form_default_response(handler_options))

}).on('focus', 'textarea[data-auth="no"]', function (_) {
    window.location.href = '/auth/login';
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
        }
    }

    $.post('/api/load/comments', replies_data, form_default_response(handler_options));

}

function hide_replies(_) {
    const tag = $(this).text(`Show ${$(this).attr('count')}`).unbind('click').click(show_replies).attr('query')
    $(`#replies-${tag}`).hide()
}

function show_replies(_) {
    const tag = $(this).text(`Hide ${$(this).attr('count')}`).unbind('click').click(hide_replies).attr('query')
    $(`#replies-${tag}`).show()
}
