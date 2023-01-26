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

        const files = $('#file')[0].files

        if (files.length === 0) {
            $(`.file-group`).addClass("has-error")
            $(`.file-error`).css('visibility', 'visible').text('Please provide a file');
            return;
        }
        const file = files[0]

        if (file.type !== 'video/mp4') {
            $(`.file-group`).addClass("has-error")
            $(`.file-error`).css('visibility', 'visible').text('Please provide a mp4 file');
            return;
        } else if (file.size > 5e7) {
            $(`.file-group`).addClass("has-error")
            $(`.file-error`).css('visibility', 'visible').text('Please provide a smaller file');
            return;
        }

        let user_data = new FormData();

        user_data.set("file", file, file.name);
        user_data.set('title', $("#title").val());
        user_data.set('description', $("#description").val())
        user_data.set('subject', $("#subject").val())
        user_data.set('free', $('#free').prop('checked') ? 'yes' : 'no')
        user_data.set('price', $("#price").val())

        let handler_options = {}

        $.upload("/api/upload/video", user_data, form_default_response(handler_options))
    });

    $("#file").on("change", function (_) {
        let filename = $(this).val();
        if (filename !== "") {
            if (filename.length > 28) {
                filename = filename.slice(12, 26) + '...'
            } else {
                filename = filename.slice(12)
            }
            $('.file-button-text').text(filename)
            $('.file-button-icon').text('video_file')
        } else {
            $('.file-button-text').text("Upload")
            $('.file-button-icon').text('upload')
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
