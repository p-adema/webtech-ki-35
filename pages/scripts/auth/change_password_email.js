$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();
        $('button.form-submit').addClass('pressed').removeClass('error')

        const parameter_list = new URLSearchParams(window.location.search)
        const user_data = {
            password: $("#password").val(),
            password_repeated: $("#password_repeated").val(),
            tag: parameter_list.get('tag')
        };

        $.post("/api/change_password_email.php", user_data, function (response_raw) {
            try {
                const response = JSON.parse(response_raw);
                console.log(response);

                if (!response.success) {
                    form_handle_errors(response.errors);
                } else {
                    $("form").html('<span class="form-success">' + response.message + "</span>")
                    setTimeout(function () {
                        // Example redirect, TODO: make auto redirect on already logged in user
                        $(location).attr('href', '/')
                    }, 5000)
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
