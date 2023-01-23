$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();
        $('button.form-submit').addClass('pressed').removeClass('error')

        const user_data = {
            email: $("#email").val(),
        };

        const handler_options = {}

        $.post("/api/forgot_password", user_data, form_default_response(handler_options));
    });
});
