$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();
        $('button.form-submit').addClass('pressed').removeClass('error')

        const user_data = {
            name: $("#name").val(),
            email: $("#email").val(),
            password: $("#password").val(),
            full_name: $("#full_name").val(),
        };

        $.post("/api/register.php", user_data, function (response_raw) {
            try {
                const response = JSON.parse(response_raw);
                console.log(response);

                if (!response.success) {
                    form_handle_errors(response.errors);
                } else {
                    $("form").html(
                        '<div class="form-success"><span>' + response.message + "</span></div>"
                    )
                    setTimeout(function () {
                        // Example redirect, TODO: make auto redirect on already logged in user (to home)
                        $(location).attr('href', '/')
                    }, 5500)
                }
            } catch (e) {
                console.log(response_raw);
                console.log(e);
                $('button.form-submit').addClass('error')
            } finally {
                $('button.form-submit').removeClass('pressed')
            }
        });
    });
});
