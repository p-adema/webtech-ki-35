$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault()
        $.post("/api/logout.php", {}, function (_) {
            $(location).attr('href', '/');
        })
    })
})
