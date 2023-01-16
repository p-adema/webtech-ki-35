$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault()
        $('button.form-submit').addClass('pressed').removeClass('error')

        $.post("/api/logout.php", {}, function (response_raw) {
            try {
                const response = JSON.parse(response_raw);
                console.log(response);
                if (!response.success) {
                    form_handle_errors(response.errors);
                } else {
                    $(location).attr('href', '/');
                }
            } catch (e) {
                console.log(response_raw);
                console.log(e);
                $('button.form-submit').addClass('error')
            } finally {
                $('button.form-submit').removeClass('pressed')
            }
            // TODO: not logged in users go to login page
        })
    })
})
