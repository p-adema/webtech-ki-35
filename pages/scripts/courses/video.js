// Description load more function
$(document).ready(function () {
    setTimeout(add_view, 5000);


    const video_tag = $('#video').attr('data-tag')
    if ($('#sidebar-load-success').attr('data-scroll') === '1') {
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
                success_handler: function (data, __) {
                    $('form#add').hide();
                    $('form#cart').show();
                    $('.cart-item-anchor').remove();
                    $('.sidebar-close').after(data.html);
                    $('.cart-item-delete').click(cart_item_delete);
                }
            }

            $.post('/api/cart/modify', user_data, form_default_response(handler_options));
        } else {

            $('button.form-submit').addClass('pressed').removeClass('error')

            window.location.href = "/checkout/review"

        }
    })
    bind_stars()

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
    const $this = $(this);
    if (!$(this).hasClass('active')) {
        $reply.css('max-height', $reply.prop('scrollHeight')).stop();
        $this.toggleClass('active')
        setTimeout(function () {
            if ($reply.css('max-height') === $reply.prop('scrollHeight') + 'px') {
                $reply.css('max-height', '');
            }
        }, 250)
    } else {
        $reply.css('max-height', $reply.prop('scrollHeight')).stop();
        setTimeout(function () {
            if ($reply.css('max-height') === $reply.prop('scrollHeight') + 'px') {
                $this.toggleClass('active')
                $reply.css('max-height', '0');
            }
        })
    }
}).on('submit', '.new-comment', function (event) {
    event.preventDefault()
    if ($(this).attr('data-auth') === 'no') {
        window.location.href = '/auth/login';
        return
    }
    const $reply_field = $(this).parent().parent();
    let user_data;
    const tag = $(this).attr('data-tag');
    if ($(this).attr('data-reply') === 'yes') {
        user_data = {
            item_tag: $('#video').attr('data-tag'),
            comment_tag: tag,
            message: $(this).find('textarea').val()
        }
        const handler_options = {
            success_handler: function (data, _) {
                $reply_field.parent().css('max-height', '0');
                $(`#new-reply-slot-${tag}`).html(data.html);
                $(`#${tag} .comment-reactions-reply-box`).removeClass('active');
            }
        }

        $.post('/api/courses/add_reply', user_data, form_default_response(handler_options))

        return
    }

    user_data = {
        item_tag: tag,
        message: $(this).find('textarea').val()
    }

    const handler_options = {
        success_handler: function (data, _) {
            $('.comments').prepend(data.html);
            $reply_field.remove();
        }
    }

    $.post('/api/courses/add_comment', user_data, form_default_response(handler_options))


}).on('focus', 'textarea[data-auth="no"]', function (_) {
    window.location.href = '/auth/login';
}).on('click', '.comment-admin-hide', function (_) {
    const $comment = $(this).parent().parent()
    let action;
    if ($comment.toggleClass('hidden').hasClass('hidden')) {
        $(this).text('visibility_off');
        action = 'hide';
    } else {
        $(this).text('visibility');
        action = 'unhide';
    }
    console.log($comment.parent())

    const user_data = {
        comment_tag: $comment.parent().attr('id'),
        action: action
    }

    const handler_options = {
        success_handler: function (data, __) {
            console.log(data);
        },
        error_handler: function (errors, __) {
            console.log('Hiding comment failed');
            console.log(errors);
        }
    }

    $.post('/api/admin/hide', user_data, handler_options);
})

function load_replies(_) {
    const $this = $(this);
    const tag = $this.unbind('click').click(show_replies).attr('query')

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
            $this.click();
        }
    }

    $.post('/api/load/comments', replies_data, form_default_response(handler_options));

}

function hide_replies(_) {
    const $this = $(this);
    const tag = $this.attr('query')
    const $replies = $(`#replies-${tag}`)
    $replies.css('max-height', $replies.prop('scrollHeight')).stop();
    setTimeout(function () {
        if ($replies.css('max-height') === $replies.prop('scrollHeight') + 'px') {
            $this.text(`Show ${$this.attr('count')}`).unbind('click').click(show_replies)
            $replies.css('max-height', '0');
        }
    })

}

function show_replies(_) {
    const tag = $(this).text(`Hide ${$(this).attr('count')}`).unbind('click').click(hide_replies).attr('query')
    const $replies = $(`#replies-${tag}`)
    $replies.css('max-height', $replies.prop('scrollHeight')).stop();
    setTimeout(function () {
        if ($replies.css('max-height') === $replies.prop('scrollHeight') + 'px') {
            $replies.css('max-height', '');
        }
    }, 250)
}

function add_view() {
    const video_tag = $('#video').attr('data-tag');
    $.post("/api/courses/add_views", {video_tag})
}
