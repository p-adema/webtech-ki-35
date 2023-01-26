$(document).ready(function () {
    $('form').submit(function (event) {
        event.preventDefault();
        $(location).attr('href', '/upload/video')
    })
})
