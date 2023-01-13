$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault()
        $.post("/api/logout.php", {}, function (response_raw) {
            const response = JSON.parse(response_raw);
            console.log(response)
            if (response.success) {
                $(location).attr('href', '/');
            }
            $('#submit-group').addClass('has-error').children('span').html(
                '<div class="help-block">' + response.errors.submit.join("<br/>") + "</div>")
        })
    })
})
