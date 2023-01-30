$(document).ready(function () {
    $('.sortable-extendor-wrapper').click(function (_) {
        const $query = $(this).parent();
        $(this).remove();
        $query.find('input').focus().keypress(function (event) {
            const val = $(this).val() + String.fromCharCode(event.which);
            if (val.length > 1) {
                search_videos(val)
            }
        }).keydown(function (event) {
            if (event.which === 8) {
                const val = $(this).val().slice(0, -1);
                if (val.length > 1) {
                    search_videos(val)
                }
            }
        })
    })
}).on('dragover', '.input-sortable-slot', function (event) {
    if (!$(this).hasClass('query-result')) {
        event.preventDefault();
    }
}).on('drop', '.input-sortable-slot', function (event) {
    const $item = $('#' + event.originalEvent.dataTransfer.getData('text/plain'));
    const dragged_html = $item.html();
    $item.html($(this).html()).removeClass('input-sortable-slot-awaiting');
    $(this).html(dragged_html);
}).on('dragstart', '.input-sortable-item', function (event) {
    const $slot = $(this).parent();
    event.originalEvent.dataTransfer.setData('text/plain', $slot.attr('id'));
    $slot.css('--slot-width', ($slot.width() + 5) + 'px').addClass('input-sortable-slot-awaiting');
}).on('drag', '.input-sortable-item', function (event) {
}).on('dragend', '.input-sortable-item', function (_) {
    $(this).parent().removeClass('input-sortable-slot-awaiting');
}).on('click', '.input-sortable-row.query-result', function (_) {
    const video_tag = $(this).find('.input-sortable-item').attr('data-tag');

    const query_data = {
        tag: video_tag,
        count: current_videos.length + 1
    }
    const $this = $(this)

    const handler_options = {
        success_handler: function (data, _) {
            $('#videos').append(data.html);
            $this.remove()
            current_videos.push(data.tag)
        },
        error_handler: function (errors, _) {
            console.log(errors);
        }
    }

    $.post('/api/load/render_course_video', query_data, form_default_response(handler_options))

}).on("change", '#type', function (_) {
    if ($('#free').prop('checked')) {
        $('#price').prop('disabled', 'true')
    } else {
        $('#price').removeAttr('disabled')
    }
}).on('click', 'fieldset input', function (event) {
    event.stopPropagation();
}).on('click', 'fieldset > div', function (event) {
    event.preventDefault();
    $(this).children('input').click()
})

function search_videos(val) {
    const query_data = {
        query: val,
        added: current_videos
    }
    const $target = $('#query-results-videos');
    const handler_options = {
        success_handler: function (data, _) {
            $target.html(data.html);
        },
        error_handler: function (errors, _) {
            console.log(errors);
            $target.text('There was a problem loading the videos')
        }
    }

    $.post('/api/load/course_videos', query_data, form_default_response(handler_options));
}

let current_videos = [];
