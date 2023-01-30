function upload_progress_handler(event) {
    let percent = 0;
    const position = event.loaded || event.position;
    if (event.lengthComputable) {
        percent = Math.ceil(position / event.total * 100);
    }
    $(".upload-progress-bar").css("width", +percent + "%");
    $(".upload-progress-text").text(percent + "%");
}

$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();
        $('button.form-submit').addClass('pressed').removeClass('error');

        const video_files = $('#file-video')[0].files

        if (video_files.length === 0) {
            $('#file-video-group').addClass("has-error")
            $('#file-video-error').css('visibility', 'visible').text('Please provide a file');
            return;
        }
        const video = video_files[0]

        if (video.type !== 'video/mp4') {
            $('#file-video-group').addClass("has-error")
            $('#file-video-error').css('visibility', 'visible').text('Please provide a mp4 file');
            return;
        } else if (video.size > 1e8) {
            $('#file-video-group').addClass("has-error")
            $('#file-video-error').css('visibility', 'visible').text('Please provide a smaller file');
            return;
        }

        const thumbnail_files = $('#file-thumbnail')[0].files

        if (thumbnail_files.length === 0) {
            $('#file-thumbnail-group').addClass("has-error")
            $('#file-thumbnail-error').css('visibility', 'visible').text('Please provide a file');
            return;
        }
        const thumbnail = thumbnail_files[0]

        if (thumbnail.type !== 'image/jpeg') {
            $('#file-thumbnail-group').addClass("has-error")
            $('#file-video-error').css('visibility', 'visible').text('Please provide a jpeg file');
            return;
        } else if (thumbnail.size > 1e5) {
            $('#file-thumbnail-group').addClass("has-error")
            $('#file-thumbnail-error').css('visibility', 'visible').text('Please provide a smaller file');
            return;
        }

        let user_data = new FormData();

        user_data.set("video", video, video.name);
        user_data.set("thumbnail", thumbnail, thumbnail.name);
        user_data.set('title', $("#title").val());
        user_data.set('description', $("#description").val())
        user_data.set('subject', $("#subject").val())
        user_data.set('free', $('#free').prop('checked') ? 'yes' : 'no')
        user_data.set('price', $("#price").val())

        let handler_options = {}

        $.upload("/api/upload/video", user_data, form_default_response(handler_options))
    });

    $(".file-group > input").on("change", function (_) {
        let filename = $(this).val();
        const id = this.id;
        if (filename !== "") {
            if (filename.length > 28) {
                filename = filename.slice(12, 26) + '...'
            } else {
                filename = filename.slice(12)
            }
            $(`#${id}-button > .file-button-text`).text(filename)
            const $icon = $(`#${id}-button > .file-button-icon`);
            $icon.text($icon.attr('data-icon'))
        } else {
            $(`#${id}-button > .file-button-text`).text("Upload")
            $(`#${id}-button > .file-button-icon`).text('upload')
        }
    })

    $("#type").on("change", function (_) {
        if ($('#free').prop('checked')) {
            $('#price').prop('disabled', 'true')
        } else {
            $('#price').removeAttr('disabled')
        }
    })

    $('fieldset input').click(function (event) {
        event.stopPropagation()
    })

    $('fieldset > div').click(function (event) {
        event.preventDefault()
        $(this).children('input').click()
    })
})
