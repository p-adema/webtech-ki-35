$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();
        $('button.form-submit').addClass('pressed').removeClass('error')

        const form_data = {
            email: $("#email").val(),
        };
        $.post("/api/forgot_password.php", form_data, function (response_raw) {
            try {
                const response = JSON.parse(response_raw);
                console.log(response);

                if (!response.success) {
                    form_handle_errors(response.errors);
                } else {
                    $("form").html('<div class="alert alert-success">' + response.message + "</div>");
                }

            } catch (e) {
                console.log(response_raw)
                console.log(e)
                $('button.form-submit').addClass('error')
            } finally {
                $('button.form-submit').removeClass('pressed')
            }
        });
    });
});
